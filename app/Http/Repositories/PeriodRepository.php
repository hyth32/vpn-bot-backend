<?php

namespace App\Http\Repositories;

use App\Models\Period;

class PeriodRepository
{
    public function index(int $offset = 0)
    {
        return Period::orderBy('id', 'asc')->offset($offset)->get();
    }
}
