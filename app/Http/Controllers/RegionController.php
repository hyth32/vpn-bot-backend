<?php

namespace App\Http\Controllers;

use App\Http\Services\RegionService;
use Illuminate\Http\Request;

class RegionController extends Controller
{
    public function __construct(
        private readonly RegionService $regionService,
    ) {}

    public function index(Request $request)
    {
        return $this->regionService->listRegions($request->offset, $request->limit);
    }
}
