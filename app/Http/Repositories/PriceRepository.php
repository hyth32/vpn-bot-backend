<?php

namespace App\Http\Repositories;

use App\Models\Price;

class PriceRepository
{
    public function index(int $regionId, int $offset, int $limit)
    {
        return Price::with('period')
            ->where('region_id', $regionId)
            ->offset($offset)
            ->limit($limit)
            ->get();
    }
}
