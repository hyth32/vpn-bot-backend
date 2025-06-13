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

    public static function fromRequest(Request $request)
    {
        return new self(
            regionId: $request->region_id,
            periodId: $request->period_id,
        );
    }
}