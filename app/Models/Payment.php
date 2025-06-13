<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'externalId',
        'status',
        'amount',
        'currency',
        'test',
        'meta',
    ];

    protected $casts = [
        'meta' => 'array',
    ];

    public function getTelegramUser(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->external_id,
        );
    }
}
