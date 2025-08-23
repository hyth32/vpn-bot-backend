<?php

namespace App\Http\Services;

use App\Http\DTOs\KeyOrderDTO;
use App\Http\DTOs\KeyResponseDTO;
use App\Http\Integrations\YooKassaService;
use App\Http\Repositories\KeyRepository;
use App\Http\Repositories\PeriodRepository;
use App\Http\Repositories\PriceRepository;
use App\Http\Repositories\RegionRepository;
use App\Http\Repositories\UserRepository;
use App\Models\Key;
use App\Support\WireGuard\WireGuardConfigParser;

class KeyService
{
    public function __construct(
        private YooKassaService $yooKassaService,
        private WireGuardService $wireGuardService,
        private KeyRepository $repository,
        private UserRepository $userRepository,
        private RegionRepository $regionRepository,
        private PeriodRepository $periodRepository,
        private PriceRepository $priceRepository,
        private WireGuardConfigParser $parser,
    ) {}

    public function listKeys(int $userId, int $offset, int $limit)
    {
        return $this->repository->index($userId, $offset, $limit);
    }

    public function showKey(int $id): Key
    {
        return $this->repository->findOne($id);
    }

    public function getConfig(int $keyId): string
    {
        $configId = $this->repository->getConfigId($keyId);
        $config = $this->wireGuardService->getPeer($configId);
        return $this->parser->parse($config);
    }

    public function buyKey(KeyOrderDTO $dto): KeyResponseDTO
    {
        $regionName = $this->regionRepository->getName($dto->getRegionId());
        $periodName = $this->periodRepository->getName($dto->getPeriodId());

        $amount = $this->priceRepository->getPrice(
            $dto->getRegionId(),
            $dto->getPeriodId(),
            $dto->getQuantity(),
        );

        $paymentLink = 'https://google.com';

        return new KeyResponseDTO(
            region_name: $regionName,
            period_name: $periodName,
            quantity: $dto->getQuantity(),
            amount: $amount,
            payment_link: $paymentLink,
        );
    }

    public function renewKey(int $keyId): KeyResponseDTO
    {
        $key = $this->repository->findOne($keyId);
        $regionName = $this->regionRepository->getName($key->region_id);
        $periodName = $this->periodRepository->getName($key->period_id);

        $amount = $this->priceRepository->getPrice($key->region_id, $key->period_id);

        $paymentLink = 'https://google.com';

        // отправка запроса к wg на update

        return new KeyResponseDTO(
            region_name: $regionName,
            period_name: $periodName,
            quantity: 1,
            amount: $amount,
            payment_link: $paymentLink,
        );
    }

    public function deleteKey(int $keyId)
    {
        $configId = $this->repository->getConfigId($keyId);
        $this->wireGuardService->removePeer($configId);
    }

    // TODO: переписать на подтверждение платежа из YooKassa
    public function acceptPayment(KeyOrderDTO $dto): array
    {
        $telegramId = $dto->getTelegramId();
        $userId = $this->userRepository->getIdFromTelegramId($telegramId);
        $userName = $this->userRepository->getNameFromTelegramId($telegramId);
        $quantity = $dto->getQuantity();

        // temp
        $expirationDays = 1;
        $existingKeysCount = $this->repository->countByUserId($userId);
        $configs = $this->wireGuardService->createPeers($userName, $existingKeysCount, $expirationDays, $quantity);

        $parsedConfigs = collect();
        foreach ($configs as $config) {
            $this->repository->create([
                'user_id' => $userId,
                'region_id' => $dto->getRegionId(),
                'period_id' => $dto->getPeriodId(),
                'expiration_date' => $config['ExpiresAt'],
                'config_id' => $config['Identifier'],
                'config_name' => $config['DisplayName'],
            ]);
            $parsedConfigs->push($this->parser->parse($config));
        }

        return $parsedConfigs->toArray();
    }
}
