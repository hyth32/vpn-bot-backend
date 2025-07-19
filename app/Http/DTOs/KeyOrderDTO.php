<?php

namespace App\Http\DTOs;

class KeyOrderDTO
{
    public string $userId;
    public int $regionId;
    public int $periodId;
    public int $quantity;

    public function __construct(int $userId, int $regionId, int $periodId, int $quantity)
    {
        $this->userId = $userId;
        $this->regionId = $regionId;
        $this->periodId = $periodId;
        $this->quantity = $quantity;
    }

    public static function fromRequest(array $data)
    {
        return new self(
            userId: $data['user_id'],
            regionId: $data['region_id'],
            periodId: $data['period_id'],
            quantity: $data['quantity'],
        );
    }

    public function toArray()
    {
        return [
            'user_id' => $this->userId,
            'region_id' => $this->regionId,
            'period_id' => $this->periodId,
        ];
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function getRegionId()
    {
        return $this->regionId;
    }

    public function getPeriodId()
    {
        return $this->periodId;
    }
}