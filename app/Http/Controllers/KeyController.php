<?php

namespace App\Http\Controllers;

use App\Http\Services\KeyService;
use Illuminate\Http\Request;

class KeyController extends Controller
{
    public function __construct(
        private readonly KeyService $keyService,
    ) {}

    public function index(Request $request)
    {
        return $this->keyService->listKeys($request->offset, $request->limit);
    }

    public function show(Request $request)
    {
        return $this->keyService->getKey($request->key_id);
    }

    public function buy(Request $request)
    {
        return $this->keyService->buyKey();
    }
}
