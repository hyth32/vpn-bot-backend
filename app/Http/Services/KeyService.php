<?php

namespace App\Http\Services;

use App\Http\DTOs\KeyOrderDTO;
use App\Http\Integrations\YooKassaService;

class KeyService
{
    public function __construct(
        private readonly YooKassaService $yooKassaService,
    ) {}

    public function listKeys(array $pagination)
    {
        //
    }

    public function getKey(int $keyId)
    {
        //
    }

    public function buyKey(KeyOrderDTO $dto)
    {
        //
    }
}
