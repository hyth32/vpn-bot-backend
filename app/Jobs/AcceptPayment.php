<?php

namespace App\Jobs;

use App\Http\DTOs\AcceptPaymentDTO;
use App\Models\Payment;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class AcceptPayment implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public readonly AcceptPaymentDTO $dto,
    ) {}

    public function handle(): void
    {
        //
    }

    public function acceptPayment()
    {
        $request = $this->createRequestWithIdempotence();


        $response = $request->post("$this->baseUrl/$paymentId/capture", $dto->toArray());

        $this->checkAndLogResponseStatus($response);
    }
}
