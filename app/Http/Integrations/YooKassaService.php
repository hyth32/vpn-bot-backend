<?php

namespace App\Http\Integrations;

use App\Models\Payment;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class YooKassaService
{
    private int $shopId;
    private string $secretKey;
    private string $baseUrl;
    private string $idempotenceKey;

    public function __construct()
    {
        $this->init();
    }

    private function init(): void
    {
        $this->shopId = config('integrations.yookassa.shop_id');
        $this->secretKey = config('integrations.yookassa.secret_key');
        $this->baseUrl = config('integrations.yookassa.base_url');
    }

    public function createPayment(array $paymentObject)
    {
        $request = $this->createRequestWithIdempotence();

        $response = $request->post($this->baseUrl, $paymentObject);

        $this->checkAndLogResponseStatus($response);

        $data = $response->json();

        $payment = Payment::createYooKassaPayment($data);

        
        // $this->checkPayment($payment->external_id);

        return json_encode(['url' => $this->getPaymentUrl($data)]);
    }

    public function createRequest(): PendingRequest
    {
        return Http::withHeaders([
            'Content-Type' => 'application/json',
        ])
        ->withBasicAuth($this->shopId, $this->secretKey);
    }

    public function createRequestWithIdempotence(): PendingRequest
    {
        return $this->createRequest()
            ->withHeaders([
                'Idempotence-Key' => $this->generateIdempotenceKey(),
            ]);
    }

    public function generateIdempotenceKey()
    {
        $this->idempotenceKey = Str::uuid()->toString();
        return $this->idempotenceKey;
    }

    public function getPaymentUrl(array $data)
    {
        return $data['confirmation']['confirmation_url'];
    }

    public function checkAndLogResponseStatus($response)
    {
        if (!$response->successful()) {
            $this->logAndThrowError($response);
        }

        $this->logSuccess($response);
    }

    public function logSuccess($response)
    {
        Log::info('YooKassa success response', [
            'status' => $response->status(),
            'body' => $response->json(),
        ]);
    }

    public function logError($response)
    {
        Log::error('YooKassa error response', [
            'status' => $response->status(),
            'body' => $response->json(),
        ]);
    }

    public function logAndThrowError($response)
    {
        $this->logError($response);
        throw new \RuntimeException('Failed to create payment: ' . $response->body());
    }
}
