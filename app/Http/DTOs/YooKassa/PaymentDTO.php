<?php

namespace App\Http\DTOs\YooKassa;

class PaymentDTO
{
    public ?string $confirmationType;
    public ?string $returnUrl;

    public function __construct(
        public string $paymentMethod,
        public float $amount,
        public ?string $currency = 'RUB',
    ) {
        $this->confirmationType = 'redirect';
        $this->returnUrl = 'https://t.me/applicevpnbot';
    }

    public function toArray(): array
    {
        return [
            'amount' => [
                'value' => $this->amount,
                'currency' => $this->currency,
            ],
            'payment_method_data' => [
                'type' => $this->paymentMethod,
            ],
            'confirmation' => [
                'type' => $this->confirmationType,
                'return_url' => $this->returnUrl,
            ],
            'capture' => true,
        ];
    }
}
