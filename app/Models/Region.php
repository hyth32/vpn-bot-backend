<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

/**
 * @OA\Schema(schema="Region", description="Регион", properties={
 *      @OA\Property(property="id", type="integer", description="ID региона"),
 *      @OA\Property(property="name", type="string", description="Название региона"),
 *      @OA\Property(property="code", type="string", description="Код региона"),
 *      @OA\Property(property="flag", type="string", description="Флаг региона"),
 * })
 */
class Region extends Model
{
    protected $fillable = [
        'name',
        'code',
        'flag',
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
