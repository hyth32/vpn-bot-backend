<?php

namespace App\Http\Controllers;

use App\Http\DTOs\PaginationDTO;
use App\Http\Requests\PaginationRequest;
use App\Http\Services\RegionService;

class RegionController extends Controller
{
    public function __construct(
        private readonly RegionService $regionService,
    ) {}

    public function index(PaginationRequest $request)
    {
        $pagination = PaginationDTO::fromRequest($request->validated());
        return $this->regionService->listRegions($pagination);
    }
}
