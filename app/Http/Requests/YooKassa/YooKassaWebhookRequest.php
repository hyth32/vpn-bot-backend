<?php

namespace App\Http\Requests\YooKassa;

use Illuminate\Foundation\Http\FormRequest;

class YooKassaWebhookRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'type' => 'string',
            'event' => 'string',
            'object' => 'array',
            'object.id' => 'string',
            'object.status' => 'string',
            'object.amount' => 'array',
            'object.amount.value' => 'string',
            'object.amount.currency' => 'string',
            'object.income_amount' => 'array',
            'object.income_amount.value' => 'string',
            'object.income_amount.currency' => 'string',
            'object.payment_method' => 'array',
            'object.payment_method.type' => 'string',
            'object.payment_method.id' => 'string',
            'object.payment_method.saved' => 'boolean',
            'object.payment_method.status' => 'string',
            'object.payment_method.title' => 'string',
            'object.payment_method.card' => 'array',
            'object.payment_method.card.first6' => 'string',
            'object.payment_method.card.last4' => 'string',
            'object.payment_method.card.expiry_year' => 'string',
            'object.payment_method.card.expiry_month' => 'string',
            'object.payment_method.card.card_type' => 'string',
            'object.payment_method.card.card_product' => 'array',
            'object.payment_method.card.card_product.code' => 'string',
            'object.payment_method.card.issuer_country' => 'string',
            'object.captured_at' => 'string',
            'object.created_at' => 'string',
            'object.test' => 'boolean',
            'object.paid' => 'boolean',
            'object.refundable' => 'boolean',
            'object.metadata' => 'array',
        ];
    }
}
