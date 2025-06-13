<?php

namespace App\Http\Controllers;

use App\Http\DTOs\KeyOrderDTO;
use App\Http\DTOs\PaginationDTO;
use App\Http\Requests\KeyOrderRequest;
use App\Http\Requests\PaginationRequest;
use App\Http\Services\KeyService;
use Illuminate\Http\Request;

class KeyController extends Controller
{
    public function __construct(
        private readonly KeyService $keyService,
    ) {}

    public function index(PaginationRequest $request)
    {
        $pagination = PaginationDTO::fromRequest($request->validated());
        return $this->keyService->listKeys($pagination);
    }

    public function show(Request $request)
    {
        return $this->keyService->getKey($request->key_id);
    }

    public function buy(KeyOrderRequest $request)
    {
        $dto = KeyOrderDTO::fromRequest($request->validated());
        return $this->keyService->buyKey($dto);
    }
}
