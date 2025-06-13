<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Region extends Model
{
    protected $fillable = [
        'name',
        'host',
        'port',
    ];

    public function getConnectionUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => "{$this->host}:{$this->port}"
        );
    }
}
