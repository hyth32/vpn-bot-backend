<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'externalId',
        'status',
        'amount',
        'currency',
        'test',
    ];

    public static function createYooKassaPayment(array $data)
    {
        return self::createPayment(
            externalId: $data['id'],
            status: $data['status'],
            amount: $data['amount']['value'],
            currency: $data['amount']['currency'],
            isTest: $data['test'],
        );
    }

    private static function createPayment(
        string $externalId,
        string $status,
        float $amount,
        string $currency,
        bool $isTest,
    ): self {
        return self::create([
            'externalId' => $externalId,
            'status' => $status,
            'amount' => $amount,
            'currency' => $currency,
            'test' => $isTest,
        ]);
    }
}
