<?php

namespace App\Http\Services;

use App\Http\Repositories\PriceRepository;

class PriceService
{
    public function __construct(
        private readonly PriceRepository $repository,
    ) {}

    public function listPrices(int $regionId, int $periodId, int $offset, int $limit)
    {
        return $this->repository->index($regionId, $periodId, $offset, $limit);
    }
}
