<?php

namespace App\Http\Repositories;

use App\Models\Period;

class PeriodRepository
{
    public function index(int $offset = 0)
    {
        return Period::orderBy('id', 'asc')->offset($offset)->get();
    }

    public function getName(int $id): ?string
    {
        return Period::where('id', $id)->value('name');
    }

    public function getExpirationDateString(int $id): string
    {
        $monthCount = Period::where('id', $id)->value('value');
        $expirationDateTime = $monthCount == 0 ? now()->addDays(2) : now()->addMonths($monthCount);
        return $expirationDateTime->toDateString();
    }

    public function getPeriodValue(int $id): int
    {
        return Period::where('id', $id)->value('value');
    }
}
