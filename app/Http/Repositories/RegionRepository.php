<?php

namespace App\Http\Repositories;

use App\Models\Region;

class RegionRepository
{
    public function index(int $offset, int $limit)
    {
        return Region::offset($offset)->limit($limit)->get();
    }
}
