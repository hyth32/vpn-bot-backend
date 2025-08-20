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
}
