<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @OA\Schema(schema="Key", description="Ключ", allOf={@OA\Schema(ref="#/components/schemas/KeyShort")}, properties={
 *     @OA\Property(property="expiration_date", type="string", format="date-time", description="Дата экспирации"),
 * })
 * 
 * @OA\Schema(schema="KeyShort", description="Ключ", properties={
 *     @OA\Property(property="id", type="integer", description="ID ключа"),
 *     @OA\Property(property="name", type="string", description="Название ключа"),
 *     @OA\Property(property="region_name", type="string", description="Название региона"),
 * })
 */
class Key extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'order_id',
        'config_id',
        'config_name',
        'expiration_date',
    ];

    protected $casts = [
        'expiration_date' => 'datetime',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
