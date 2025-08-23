<?php

namespace App\Http\DTOs;

class KeyOrderDTO extends BaseDTO
{
    public function __construct(
        public string $telegram_id,
        public int $region_id,
        public int $period_id,
        public int $quantity,
    ) {}

    public static function fromRequest(array $data)
    {
        return new self(
            telegram_id: $data['telegram_id'],
            region_id: $data['region_id'],
            period_id: $data['period_id'],
            quantity: $data['quantity'],
        );
    }

    public function getTelegramId()
    {
        return $this->telegram_id;
    }

    public function getRegionId()
    {
        return $this->region_id;
    }

    public function getPeriodId()
    {
        return $this->period_id;
    }

    public function getQuantity()
    {
        return $this->quantity;
    }
}