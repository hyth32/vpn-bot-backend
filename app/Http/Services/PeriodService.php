<?php

namespace App\Http\Services;

use App\Http\Repositories\PeriodRepository;

class PeriodService
{
    public function __construct(
        private PeriodRepository $repository,
    ) {}

    public function listPeriods()
    {
        return $this->repository->index();
    }
}
