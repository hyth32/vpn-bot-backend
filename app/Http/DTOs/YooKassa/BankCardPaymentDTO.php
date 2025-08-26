<?php

namespace App\Http\DTOs\YooKassa;

class BankCardPaymentDTO extends PaymentDTO
{
    public function __construct(float $amount)
    {
        parent::__construct('bank_card', $amount);
    }
}
