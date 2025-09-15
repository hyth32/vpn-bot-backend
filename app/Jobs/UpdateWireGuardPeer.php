<?php

namespace App\Jobs;

use App\Http\Repositories\KeyRepository;
use App\Http\Services\WireGuardService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class UpdateWireGuardPeer implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public int $keyId,
        public int $monthsToAdd,
    ) {
        $this->queue = 'wireguard';
    }

    public function handle(
        WireGuardService $wireGuardService,
        KeyRepository $keyRepository,
    ): void {
        $configId = $keyRepository->getConfigId($this->keyId);
        $updated = $wireGuardService->updatePeer($configId, $this->monthsToAdd);
    
        if ($updated) {
            $updatedPeer = $wireGuardService->getPeer($configId);
            $keyRepository->setExpirationDate($this->keyId, $updatedPeer['ExpiresAt']);
        }
    }
}
