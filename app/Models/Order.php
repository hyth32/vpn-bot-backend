<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'external_id',
        'user_id',
        'amount',
        'currency',
        'test',
        'paid',
        'metadata',
        'region_id',
        'period_id',
        'key_count',
        'free',
        'renew',
        'key_id',
        'renewed_key_id',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    public function period(): BelongsTo
    {
        return $this->belongsTo(Period::class);
    }

    public function keys(): HasMany
    {
        return $this->hasMany(Key::class);
    }

    public function renewedKey(): BelongsTo
    {
        return $this->belongsTo(Key::class, 'renewed_key_id');
    }
}
