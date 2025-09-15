<?php

namespace App\Http\Services;

use App\Http\Repositories\KeyRepository;
use App\Http\Repositories\WireGuardRepository;
use Carbon\Carbon;

class WireGuardService
{
    public function __construct(
        private WireGuardRepository $repository,
        private KeyRepository $keyRepository,
    ) {}

    public function createPeers(string $userName, int $existingCount, string $expirationDate, int $keysCount): array
    {
        $baseConfigName = $userName . '-';
        
        $configs = [];
        for ($i = 0; $i < $keysCount; $i++) {
            $configName = $baseConfigName . $existingCount + $i;
            $configs[] = $this->createPeer($configName, $expirationDate);
        }

        return $configs;
    }

    public function createPeer(string $configName, string $expirationDate): array
    {
        return $this->repository->createConfig($configName, $expirationDate);
    }

    public function updatePeer(string $configId, int $monthsToAdd)
    {
        return $this->repository->renewConfig($configId, $monthsToAdd);
    }
    
    public function getPeer(string $configId): array
    {
        return $this->repository->findConfig($configId);
    }

    public function getPeerExpirationDate(string $configId): string
    {
        $config = $this->repository->findConfig($configId);
        return Carbon::parse($config['ExpiresAt'])->toDateTimeString();
    }

    public function getMetrics(string $configId): array
    {
        $metrics = $this->repository->getPeerMetrics($configId);
        return $metrics;
    }

    public function removePeer(string $configId)
    {
        $isDeleted = $this->repository->deleteConfig($configId);
        if (!$isDeleted) {
            abort(500, 'Не удалось удалить ключ');
        }

        if ($isDeleted) {
            $this->keyRepository->deleteByConfigId($configId);
        }
    }
}
