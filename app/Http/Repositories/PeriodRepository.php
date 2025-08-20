<?php

namespace App\Http\Repositories;

use App\Models\Period;

class PeriodRepository
{
    public function index()
    {
        return Period::orderBy('id', 'asc')->get();
    }
}
