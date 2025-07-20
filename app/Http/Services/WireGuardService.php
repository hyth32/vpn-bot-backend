<?php

namespace App\Http\Services;

use App\Http\Repositories\WireGuardRepository;
use App\Models\Key;
use App\Models\User;

class WireGuardService
{
    public function __construct(
        private readonly WireGuardRepository $repository,
    ) {}

    public function createPeer(User $user, int $expirationDays)
    {
        $configName = $this->getConfigName($user);

        return $this->repository->createConfig($configName, $expirationDays);
    }
    
    public function getPeer(Key $key)
    {
        $configId = $key->key;
        return $this->repository->findConfig($configId);
    }
    
    public function getConfigName(User $user)
    {
        $keysCount = $user->keys()->count() + 1;
        return "{$user->name}-{$keysCount}";
    }
}
