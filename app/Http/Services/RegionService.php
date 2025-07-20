<?php

namespace App\Http\Services;

use App\Http\Repositories\RegionRepository;

class RegionService
{
    public function __construct(
        private readonly RegionRepository $repository,
    ) {}

    public function listRegions(array $data)
    {
        return $this->repository->index($data['offset'], $data['limit']);
    }
}
