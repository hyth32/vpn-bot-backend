<?php

namespace App\Http\Repositories;

use App\Models\Price;

class PriceRepository
{
    public function index(int $regionId, int $periodId, int $offset, int $limit)
    {
        return Price::with('period')
            ->where('region_id', $regionId)
            ->where('period_id', $periodId)
            ->offset($offset)
            ->limit($limit)
            ->orderBy('key_count')
            ->get();
    }

    public function getPrice(int $regionId, int $periodId, ?int $keyCount = 1): ?float
    {
        return Price::where('region_id' , $regionId)
            ->where('period_id', $periodId)
            ->where('key_count', $keyCount)
            ->value('amount');
    }
}
