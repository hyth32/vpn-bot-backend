<?php

namespace App\Http\Integrations;

use App\Http\DTOs\YooKassa\PaymentDto;
use App\Http\DTOs\YooKassa\YooKassaCreateOrderResponseDTO;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class YooKassaService
{
    public function createPayment(PaymentDto $payment)
    {
        $idempotenceKey = Str::uuid7()->toString();

        $createPaymentUrl = config('yookassa.url') . '/payments';
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . config('yookassa.oauth_token'),
            'Idempotence-Key' => $idempotenceKey,
        ])
        ->withBody(json_encode($payment->toArray()))
        ->post($createPaymentUrl)
        ->json();

        return YooKassaCreateOrderResponseDTO::fromArray($response);
    }
}
