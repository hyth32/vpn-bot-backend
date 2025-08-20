<?php

namespace App\Http\Services;

use App\Http\Repositories\PeriodRepository;
use App\Models\User;

class PeriodService
{
    public function __construct(
        private PeriodRepository $repository,
    ) {}

    public function listPeriods(User $user)
    {
        $offset = (int) $user->isFreeKeyUsed();
        return $this->repository->index($offset);
    }
}
