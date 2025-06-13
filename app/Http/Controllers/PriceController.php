<?php

namespace App\Http\Controllers;

use App\Http\DTOs\PaginationDTO;
use App\Http\Requests\PriceListRequest;
use App\Http\Services\PriceService;

class PriceController extends Controller
{
    public function __construct(
        private readonly PriceService $priceService,
    ) {}

    public function index(PriceListRequest $request)
    {
        $data = $request->validated();
        $pagination = PaginationDTO::fromRequest($data);
        return $this->priceService->listPrices($pagination, $data['region_id']);
    }
}
