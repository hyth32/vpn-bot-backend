<?php

namespace App\Http\Controllers;

use App\Http\Services\PriceService;
use Illuminate\Http\Request;

class PriceController extends Controller
{
    public function __construct(
        private readonly PriceService $priceService,
    ) {}

    public function index(Request $request)
    {
        return $this->priceService->listPrices($request->offset, $request->limit, $request->region_id);
    }
}
