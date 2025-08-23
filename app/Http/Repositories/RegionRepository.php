<?php

namespace App\Http\Repositories;

use App\Models\Region;

class RegionRepository
{
    public function index(int $offset, int $limit)
    {
        return Region::offset($offset)->limit($limit)->get();
    }

    public function getName(int $regionId): ?string
    {
        return Region::where('id', $regionId)->value('name');
    }
}
