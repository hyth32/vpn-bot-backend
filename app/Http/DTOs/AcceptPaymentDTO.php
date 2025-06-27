<?php

namespace App\Http\DTOs;

class AcceptPaymentDTO
{
    public function __construct(
        public readonly float $amount,
        public readonly string $currency,
    ) {}

    public function toArray(): array
    {
        return [
            'amount' => [
                'value' => $this->amount,
                'currency' => $this->currency,
            ]
        ];
    }

    public static function create(
        float $amount,
        string $currency,
    ): self {
        return new self(
            amount: $amount,
            currency: $currency,
        );
    }
}
