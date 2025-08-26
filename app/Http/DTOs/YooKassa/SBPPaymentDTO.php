<?php

namespace App\Http\DTOs\YooKassa;

class SBPPaymentDTO extends PaymentDTO
{
    public function __construct(float $amount)
    {
        parent::__construct('sbp', $amount);
    }
}
