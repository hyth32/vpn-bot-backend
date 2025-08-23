<?php

namespace App\Http\Services;

use App\Http\DTOs\KeyOrderDTO;
use App\Http\Integrations\YooKassaService;
use App\Http\Repositories\KeyRepository;
use App\Models\Period;
use App\Models\Price;
use App\Models\Region;
use App\Models\User;
use App\Support\WireGuard\WireGuardConfigParser;

class KeyService
{
    public function __construct(
        private YooKassaService $yooKassaService,
        private WireGuardService $wireGuardService,
        private KeyRepository $repository,
        private WireGuardConfigParser $parser,
    ) {}

    public function listKeys(int $userId, int $offset, int $limit)
    {
        return $this->repository->index($userId, $offset, $limit);
    }

    public function showKey(int $id)
    {
        return $this->repository->findOne($id);
    }

    public function getConfig(int $keyId)
    {
        $key = $this->repository->findOne($keyId);
        $config = $this->wireGuardService->getPeer($key);
        return $this->parser->parse($config);
    }

    public function buyKey(KeyOrderDTO $dto)
    {
        $region = Region::find($dto->regionId);
        $period = Period::find($dto->periodId);

        $amount = Price::getAmount($dto->getRegionId(), $dto->getPeriodId(), $dto->getQuantity());
        $paymentLink = 'https://google.com';

        return [
            'region_name' => $region->name,
            'period_name' => $period->name,
            'quantity' => $dto->getQuantity(),
            'amount' => $amount,
            'payment_link' => $paymentLink,
        ];
    }

    // TODO: переписать на подтверждение платежа из YooKassa
    public function acceptPayment(User $user, KeyOrderDTO $dto)
    {
        $amount = Price::getAmount($dto->getRegionId(), $dto->getPeriodId(), $dto->getQuantity());
        $config = $this->wireGuardService->createPeer($user, $amount);

        $user->keys()->create([
            'region_id' => $dto->getRegionId(),
            'period_id' => $dto->getPeriodId(),
            'expiration_date' => $config['ExpiresAt'],
            'key' => $config['Identifier'],
        ]);

        return $this->parser->parse($config);
    }
}
