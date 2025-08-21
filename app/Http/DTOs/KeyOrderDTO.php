<?php

namespace App\Http\DTOs;

class KeyOrderDTO
{
    public string $telegramId;
    public int $regionId;
    public int $periodId;
    public int $quantity;

    public function __construct(int $telegramId, int $regionId, int $periodId, int $quantity)
    {
        $this->telegramId = $telegramId;
        $this->regionId = $regionId;
        $this->periodId = $periodId;
        $this->quantity = $quantity;
    }

    public static function fromRequest(array $data)
    {
        return new self(
            telegramId: $data['telegram_id'],
            regionId: $data['region_id'],
            periodId: $data['period_id'],
            quantity: $data['quantity'],
        );
    }

    public function toArray()
    {
        return [
            'telegram_id' => $this->telegramId,
            'region_id' => $this->regionId,
            'period_id' => $this->periodId,
        ];
    }

    public function getTelegramId()
    {
        return $this->telegramId;
    }

    public function getRegionId()
    {
        return $this->regionId;
    }

    public function getPeriodId()
    {
        return $this->periodId;
    }

    public function getQuantity()
    {
        return $this->quantity;
    }
}