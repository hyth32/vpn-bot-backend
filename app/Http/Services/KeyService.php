<?php

namespace App\Http\Services;

use App\Events\OrderCreated;
use App\Http\DTOs\KeyOrderDTO;
use App\Http\DTOs\KeyResponseDTO;
use App\Http\DTOs\YooKassa\BankCardPaymentDTO;
use App\Http\Integrations\YooKassaService;
use App\Http\Repositories\KeyRepository;
use App\Http\Repositories\PeriodRepository;
use App\Http\Repositories\PriceRepository;
use App\Http\Repositories\RegionRepository;
use App\Http\Repositories\UserRepository;
use App\Models\Key;
use App\Models\Order;
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

    public function buyKey(KeyOrderDTO $dto)
    {
        $regionName = $this->regionRepository->getName($dto->getRegionId());
        $periodName = $this->periodRepository->getName($dto->getPeriodId());

        $amount = $this->priceRepository->getPrice(
            $dto->getRegionId(),
            $dto->getPeriodId(),
            $dto->getQuantity(),
        );

        $payment = new BankCardPaymentDTO($amount);
        $paymentResponse = $this->yooKassaService->createPayment($payment);

        $userId = $this->userRepository->getIdFromTelegramId($dto->getTelegramId());

        Order::create([
            'user_id' => $userId,
            'external_id' => $paymentResponse->getExternalId(),
            'amount' => $paymentResponse->getAmount(),
            'test' => $paymentResponse->isTest(),
            'paid' => $paymentResponse->isPaid(),
            'metadata' => $paymentResponse->getMetadata(),
            'region_id' => $dto->getRegionId(),
            'period_id' => $dto->getPeriodId(),
            'key_count' => $dto->getQuantity(),
        ]);

        return new KeyResponseDTO(
            region_name: $regionName,
            period_name: $periodName,
            quantity: $dto->getQuantity(),
            amount: $amount,
            payment_link: $paymentResponse->getPaymentUrl(),
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
}
