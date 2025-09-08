<?php

namespace App\Http\Integrations;

use App\Http\DTOs\KeyOrderDTO;
use App\Http\Enums\OrderMessageAction;
use App\Http\Repositories\OrderRepository;
use App\Http\Repositories\PeriodRepository;
use App\Http\Repositories\UserRepository;
use App\Http\Requests\YooKassa\YooKassaWebhookRequest;
use App\Http\Services\WireGuardService;
use App\Jobs\CreateWireGuardPeer;
use App\Jobs\SendOrderStatusMessage;
use App\Jobs\UpdateWireGuardPeer;
use Illuminate\Support\Facades\Bus;

class YooKassaCallbackController
{
    public function __construct(
        private UserRepository $userRepository,
        private OrderRepository $orderRepository,
        private PeriodRepository $periodRepository,
        private WireGuardService $wireGuardService,
    ) {}

    public function handle(YooKassaWebhookRequest $request)
    {
        $data = collect($request->validated());

        $dataObject = collect($data->get('object'));

        $orderExternalId = $dataObject->get('id');

        $order = $this->orderRepository->setPaidStatus($orderExternalId);

        $telegramId = $this->userRepository->getTelegramId($order->user_id);

        $keyOrderDto = new KeyOrderDTO(
            telegram_id: $telegramId,
            region_id: $order->region_id,
            period_id: $order->period_id,
            quantity: $order->key_count,
        );

        $expirationDate = $this->periodRepository->getExpirationDateString($order->period_id);

        Bus::chain([
            $order->renew
                ? (new UpdateWireGuardPeer($order->renew_key_id, $expirationDate))
                : (new CreateWireGuardPeer($order->id, $expirationDate, $keyOrderDto)),
            (new SendOrderStatusMessage([
                'success' => true,
                'telegram_id' => $telegramId,
                'action' => $order->renew
                    ? OrderMessageAction::Renew->value
                    : OrderMessageAction::Create->value,
            ])),
        ])->dispatch();
    }
}
