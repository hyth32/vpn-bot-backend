<?php

namespace App\Http\Services;

use App\Http\DTOs\KeyOrderDTO;
use App\Http\Repositories\WireGuardRepository;
use App\Models\User;

class WireGuardService
{
    public function __construct(
        private readonly WireGuardRepository $repository,
    ) {}

    public function createPeer(KeyOrderDTO $keyDto, int $expirationDays)
    {
        $configName = $this->getConfigName($keyDto->getUserId());
        $config = $this->repository->createConfig($configName, $expirationDays);

        return $config;
    }

    public function getConfigName(int $userId)
    {
        $user = User::where('telegram_id', $userId)->firstOrFail();
        $keysCount = $user->keys()->count() + 1;
        return "{$user->name}-{$keysCount}";
    }
}
