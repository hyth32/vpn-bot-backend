<?php

namespace App\Http\DTOs;

class PaymentDTO
{
    public function __construct(
        public readonly float $amount,
        public readonly ?string $description,
        public readonly ?string $paymentMethod = 'bank_card',
        public readonly ?string $currency = 'RUB',
        public readonly ?string $returnUrl = null,
    ) {}

    public function toArray(): array
    {
        $data = [
            'amount' => [
                'value' => $this->amount,
                'currency' => $this->currency,
            ],
            'payment_method_data' => [
                'type' => $this->paymentMethod,
            ],
            'description' => $this->description,
        ];

        if ($this->returnUrl) {
            $data['confirmation'] = [
                'type' => 'redirect',
                'return_url' => $this->returnUrl,
            ];
        }

        return $data;
    }

    public static function create(
        float $amount,
        ?string $description = null,
        ?string $paymentMethod = 'bank_card',
        ?string $currency = 'RUB',
        ?string $returnUrl = 'https://example.com',
    ): self {
        return new self(
            amount: $amount,
            description: $description,
            paymentMethod: $paymentMethod,
            currency: $currency,
            returnUrl: $returnUrl,
        );
    }
}
