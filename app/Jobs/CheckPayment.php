<?php

namespace App\Jobs;

use App\Http\DTOs\AcceptPaymentDTO;
use App\Models\Payment;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class CheckPayment implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public readonly int $paymentId,
    ) {}

    public function handle(): void
    {
        //
    }

    public function checkPayment()
    {
        //TODO: добавить повторную проверку изменения статуса
        // с pending на waiting_for_capture

        $request = $this->createRequest();

        $response = $request->get("$this->baseUrl/$this->paymentId");

        $this->checkAndLogResponseStatus($response);

        //TODO: если статус waiting_for_capture - вызывать

        $this->acceptPayment($paymentId);
    }

    public function checkPaymentStatus($response)
    {

    }

    public function createAcceptPaymentObject()
    {
        $payment = Payment::where('externalId', $this->paymentId)->first();
        return AcceptPaymentDTO::create($payment->amount, $payment->currency);
    }
}
