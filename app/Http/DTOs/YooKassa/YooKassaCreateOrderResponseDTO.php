<?php

namespace App\Http\DTOs\YooKassa;

class YooKassaCreateOrderResponseDTO
{
    public function __construct(
        public string $id,
        public string $status,
        public bool $paid,
        public string $amount,
        public string $currency,
        public string $confirmationUrl,
        public string $createdAt,
        public ?string $description,
        public ?string $metadata,
        public string $paymentType,
        public bool $paymentSaved,
        public bool $refundable,
        public bool $test,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'],
            status: $data['status'],
            paid: $data['paid'],
            amount: $data['amount']['value'],
            currency: $data['amount']['currency'],
            confirmationUrl: $data['confirmation']['confirmation_url'],
            createdAt: $data['created_at'],
            description: $data['description'] ?? null,
            metadata: json_encode($data['metadata']),
            paymentType: $data['payment_method']['type'],
            paymentSaved: $data['payment_method']['saved'],
            refundable: $data['refundable'],
            test: $data['test'],
        );
    }

    public function getPaymentUrl(): string
    {
        return $this->confirmationUrl;
    }
}
