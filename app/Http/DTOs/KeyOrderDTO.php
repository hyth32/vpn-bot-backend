<?php

namespace App\Http\DTOs;

use Illuminate\Http\Request;

class KeyOrderDTO
{
    public int $regionId;
    public int $periodId;

    public function __construct(int $regionId, int $periodId)
    {
        $this->regionId = $regionId;
        $this->periodId = $periodId;
    }

    public static function fromRequest(array $data)
    {
        return new self(
            regionId: $data['region_id'],
            periodId: $data['period_id'],
        );
    }
}