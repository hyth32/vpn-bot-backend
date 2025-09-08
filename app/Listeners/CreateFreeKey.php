<?php

namespace App\Listeners;

use App\Events\FreeKeyUsed;
use App\Http\Repositories\UserRepository;
use App\Jobs\CreateWireGuardPeer;
use App\Jobs\SendOrderStatusMessage;
use Illuminate\Support\Facades\Bus;

class CreateFreeKey
{
    public function __construct(
        private UserRepository $userRepository,
    ) {}

    public function handle(FreeKeyUsed $event): void
    {
        $orderId = $event->orderId;
        $keyOrderDto = $event->dto;
        $expirationDate = $event->expirationDate;

        $telegramId = $keyOrderDto->getTelegramId();

        $this->userRepository->markFreeKeyUsed($telegramId);

        Bus::chain([
            (new CreateWireGuardPeer($orderId, $expirationDate, $keyOrderDto)),

            // fn () => $this->userRepository->markFreeKeyUsed($telegramId),
            
            (new SendOrderStatusMessage([
                'success' => 'true',
                'telegram_id' => $telegramId,
                'free' => true,
            ])),
        ])->dispatch();
    }
}
