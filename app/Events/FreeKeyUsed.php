<?php

namespace App\Events;

use App\Http\DTOs\KeyOrderDTO;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class FreeKeyUsed
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public int $orderId,
        public KeyOrderDTO $dto,
        public string $expirationDate,
    ) {}
}
