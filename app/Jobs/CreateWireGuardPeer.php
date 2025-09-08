<?php

namespace App\Jobs;

use App\Http\DTOs\KeyOrderDTO;
use App\Http\Repositories\OrderRepository;
use App\Http\Repositories\UserRepository;
use App\Http\Services\WireGuardService;
use App\Models\Key;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;

class CreateWireGuardPeer implements ShouldQueue
{
    use Queueable, InteractsWithQueue, Dispatchable;

    public function __construct(
        public int $orderId,
        public string $expirationDate,
        public KeyOrderDTO $dto,
    ) {
        $this->queue = 'wireguard';
    }

    public function handle(
        WireGuardService $wireGuardService,
        UserRepository $userRepository,
        OrderRepository $orderRepository,
    ): void {
        $userId = $userRepository->getIdFromTelegramId($this->dto->getTelegramId());
        $userName = $userRepository->getNameFromTelegramId($this->dto->getTelegramId());
        $existingKeysCount = $orderRepository->countKeysByUserId($userId);

        $configs = $wireGuardService->createPeers(
            $userName,
            $existingKeysCount,
            $this->expirationDate,
            $this->dto->getQuantity(),
        );

        foreach ($configs as $config) {
            Key::create([
                'order_id' => $this->orderId,
                'expiration_date' => $config['ExpiresAt'],
                'config_id' => $config['Identifier'],
                'config_name' => $config['DisplayName'],
            ]);
        }
    }
}
