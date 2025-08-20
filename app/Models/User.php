<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'telegram_id',
        'last_active_at',
        'free_key_used',
    ];

    protected function casts(): array
    {
        return [
            'last_active_at' => 'datetime',
        ];
    }

    public function isFreeKeyUsed(): bool
    {
        return $this->free_key_used;
    }

    public function setFreeKeyUsed()
    {
        $this->update(['free_key_used' => true]);
    }

    public function keys(): HasMany
    {
        return $this->hasMany(Key::class);
    }
}
