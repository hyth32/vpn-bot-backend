<?php

namespace App\Http\Services;

use App\Http\Repositories\PriceRepository;

class PriceService
{
    public function __construct(
        private readonly PriceRepository $repository,
    ) {}

    public function listPrices(array $data)
    {
        return $this->repository->index($data['region_id'], $data['offset'], $data['limit']);
    }
}
