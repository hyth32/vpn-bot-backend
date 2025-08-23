<?php

namespace App\Http\Services;

use App\Http\Repositories\KeyRepository;
use App\Http\Repositories\WireGuardRepository;

class WireGuardService
{
    public function __construct(
        private WireGuardRepository $repository,
        private KeyRepository $keyRepository,
    ) {}

    public function createPeer(int $userId, string $userName, int $expirationDays)
    {
        $keysCount = $this->keyRepository->countByUserId($userId);
        $configName = "{$userName}-{$keysCount}";
        return $this->repository->createConfig($configName, $expirationDays);
    }
    
    public function getPeer(string $configId)
    {
        return $this->repository->findConfig($configId);
    }
}
