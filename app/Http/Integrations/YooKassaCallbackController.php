<?php

namespace App\Http\Integrations;

use App\Http\DTOs\KeyOrderDTO;
use App\Http\Repositories\OrderRepository;
use App\Http\Repositories\PeriodRepository;
use App\Http\Repositories\UserRepository;
use App\Http\Requests\YooKassa\YooKassaWebhookRequest;
use App\Http\Services\WireGuardService;
use App\Jobs\CreateWireGuardPeer;
use App\Jobs\SendOrderStatusMessage;
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

        $amountObject = collect($dataObject->get('amount'));

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
            (new CreateWireGuardPeer($order->id, $expirationDate, $keyOrderDto))
                ->onQueue('wireguard'),
            (new SendOrderStatusMessage([
                'success' => true,
                'telegram_id' => $telegramId,
            ]))->onQueue('notifications'),
        ])->dispatch();
    }
}
