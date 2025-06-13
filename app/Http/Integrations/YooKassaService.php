<?php

namespace App\Http\Integrations;

use App\Models\Payment;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class YooKassaService
{
    private int $shopId;
    private string $secretKey;
    private string $baseUrl;

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
        $idempotenceKey = Str::uuid()->toString();

        $request = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Idempotence-Key' => $idempotenceKey,
        ])
        ->withBasicAuth($this->shopId, $this->secretKey);

        $response = $request->post($this->baseUrl, $paymentObject);

        $this->checkAndLogResponseStatus($response);

        $data = $response->json();

        $payment = Payment::create([
            'externalId' => $data['id'],
            'status' => $data['status'],
            'amount' => $data['amount']['value'],
            'currency' => $data['amount']['currency'],
            'test' => $data['test'],
        ]);

        // $this->checkPayment($payment->external_id);

        \Log::info(['url' => $data['confirmation']['confirmation_url']]);

        return json_encode(['url' => $data['confirmation']['confirmation_url']]);
    }

    public function checkPayment(string $paymentId)
    {
        //TODO: добавить повторную проверку изменения статуса
        // с pending на waiting_for_capture

        $request = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])
        ->withBasicAuth($this->shopId, $this->secretKey);

        $response = $request->get("$this->baseUrl/$paymentId");

        $this->checkAndLogResponseStatus($response);

        //TODO: если статус waiting_for_capture - вызывать
        $this->acceptPayment($paymentId);
    }

    public function acceptPayment(string $paymentId)
    {
        $idempotenceKey = Str::uuid()->toString();

        $request = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Idempotence-Key' => $idempotenceKey,
        ])
        ->withBasicAuth($this->shopId, $this->secretKey);

        $payment = Payment::where('externalId', $paymentId)->first();

        $mockPaymentObject = [
            'amount' => [
                'value' => $payment->amount,
                'currency' => $payment->currency,
            ]
        ];
        $response = $request->post("$this->baseUrl/$paymentId/capture", $mockPaymentObject);

        $this->checkAndLogResponseStatus($response);
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

    public function logAndThrowError($response)
    {
        Log::error('YooKassa error', [
            'status' => $response->status(),
            'body' => $response->json(),
        ]);
        throw new \RuntimeException('Failed to create payment: ' . $response->body());
    }
}
