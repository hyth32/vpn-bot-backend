<?php

namespace App\Http\Integrations;

use App\Http\Repositories\OrderRepository;
use App\Http\Repositories\UserRepository;
use App\Http\Requests\YooKassa\YooKassaWebhookRequest;
use App\Jobs\SendOrderStatusMessage;

class YooKassaCallbackController
{
    public function __construct(
        private UserRepository $userRepository,
        private OrderRepository $orderRepository,
    ) {}

    public function handle(YooKassaWebhookRequest $request)
    {
        $data = collect($request->validated());

        $dataObject = collect($data->get('object'));

        $amountObject = collect($dataObject->get('amount'));

        $orderExternalId = $dataObject->get('id');

        $amount = $amountObject->get('amount');
        $currency = $amountObject->get('currency');

        $order = $this->orderRepository->setPaidStatus($orderExternalId);
        $telegramId = $this->userRepository->getTelegramId($order->user_id);

        info('PARSED DATA: ' . json_encode($data) . '\n');

        dispatch(new SendOrderStatusMessage([
            'success' => true,
            'telegram_id' => $telegramId,
            'amount' => $amount,
            'currency' => $currency,
        ]));
    }
}
