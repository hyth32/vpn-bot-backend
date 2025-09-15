<?php

namespace App\Http\Services;

use App\Events\FreeKeyUsed;
use App\Http\DTOs\KeyOrderDTO;
use App\Http\DTOs\KeyResponseDTO;
use App\Http\DTOs\YooKassa\BankCardPaymentDTO;
use App\Http\Integrations\YooKassaService;
use App\Http\Repositories\KeyRepository;
use App\Http\Repositories\OrderRepository;
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
        private OrderRepository $orderRepository,
        private UserRepository $userRepository,
        private RegionRepository $regionRepository,
        private PeriodRepository $periodRepository,
        private PriceRepository $priceRepository,
        private WireGuardConfigParser $parser,
    ) {}

    public function listKeys(int $userId, int $offset, int $limit)
    {
        $userOrderIds = Order::where('user_id', $userId)->pluck('id');
        $keysQuery = Key::whereIn('order_id', $userOrderIds);
        $totalCount = $keysQuery->count();
        
        return [
            'total' => $totalCount,
            'keys' => $keysQuery->offset($offset)->limit($limit)->get(),
        ];
    }

    public function showKey(int $id): Key
    {
        $configId = $this->repository->getConfigId($id);
        $expirationDate = $this->wireGuardService->getPeerExpirationDate($configId);

        $key = $this->repository->findOne($id);
        $key->update(['expiration_date' => $expirationDate]);

        return $key->refresh();
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

    public function getFreeKey(KeyOrderDTO $dto): void
    {
        $userId = $this->userRepository->getIdFromTelegramId($dto->getTelegramId());

        $order = Order::create([
            'user_id' => $userId,
            'amount' => 0,
            'test' => false,
            'paid' => true,
            'region_id' => $dto->getRegionId(),
            'period_id' => $dto->getPeriodId(),
            'key_count' => $dto->getQuantity(),
            'free' => true,
        ]);

        $expirationDateString = $this->periodRepository->getExpirationDateString($dto->getPeriodId());
        
        event(new FreeKeyUsed($order->id, $dto, $expirationDateString));
    }

    public function renewKey(string $telegramId, int $keyId): KeyResponseDTO
    {
        $key = $this->repository->findOne($keyId);
        $order = $key->order;
        $regionId = $order->region_id;
        $periodId = $order->period_id;

        $regionName = $this->regionRepository->getName($regionId);
        $periodName = $this->periodRepository->getName($periodId);

        $amount = $this->priceRepository->getPrice($regionId, $periodId);

        $payment = new BankCardPaymentDTO($amount);
        $paymentResponse = $this->yooKassaService->createPayment($payment);

        $userId = $this->userRepository->getIdFromTelegramId($telegramId);

        Order::create([
            'user_id' => $userId,
            'external_id' => $paymentResponse->getExternalId(),
            'amount' => $paymentResponse->getAmount(),
            'test' => $paymentResponse->isTest(),
            'paid' => $paymentResponse->isPaid(),
            'metadata' => $paymentResponse->getMetadata(),
            'region_id' => $regionId,
            'period_id' => $periodId,
            'key_count' => 1,
            'renew' => true,
            'renewed_key_id' => $keyId,
        ]);

        return new KeyResponseDTO(
            region_name: $regionName,
            period_name: $periodName,
            quantity: 1,
            amount: $amount,
            payment_link: $paymentResponse->getPaymentUrl(),
        );
    }

    public function deleteKey(int $keyId)
    {
        $configId = $this->repository->getConfigId($keyId);
        $this->wireGuardService->removePeer($configId);
    }
}
