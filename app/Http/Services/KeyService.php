<?php

namespace App\Http\Services;

use App\Http\DTOs\KeyOrderDTO;
use App\Http\Integrations\YooKassaService;
use App\Models\Price;
use App\Models\User;

class KeyService
{
    public function __construct(
        private readonly YooKassaService $yooKassaService,
        private readonly WireGuardService $wireGuardService,
    ) {}

    public function listKeys(array $pagination)
    {
        //
    }

    public function getKey(int $keyId)
    {
        //
    }

    public function buyKey(KeyOrderDTO $dto)
    {
        $user = User::where('telegram_id', $dto->getUserId())->firstOrFail();
        $amount = Price::getAmount($dto->getRegionId(), $dto->getPeriodId());

        $config = $this->wireGuardService->createPeer($user, $amount);

        $user->keys()->create([
            'region_id' => $dto->getRegionId(),
            'period_id' => $dto->getPeriodId(),
            'expiration_date' => $config['ExpiresAt'],
            'key' => $config['Identifier'],
        ]);

        return $config;
    }

    
}
