<?php

namespace App\Http\Repositories;

use App\Models\Key;
use Illuminate\Database\Eloquent\Collection;

class KeyRepository
{
    public function create(array $data): Key
    {
        return Key::create($data);
    }

    public function index(int $orderId, int $offset, int $limit)
    {
        return Key::with('region')
            ->where('order_id', $orderId)
            ->offset($offset)
            ->limit($limit)
            ->get();
    }

    public function findOne(int $id): Key
    {
        return Key::with('order')->find($id);
    }

    public function deleteByConfigId(string $configId)
    {
        return Key::where('config_id', $configId)->delete();
    }

    public function existsByUserId(int $userId, int $keyId): bool
    {
        return Key::where('id', $keyId)->where('user_id', $userId)->exists();
    }

    public function getConfigId(int $keyId): ?string
    {
        return Key::where('id', $keyId)->value('config_id');
    }

    public function getConfigName(int $id): ?string
    {
        return Key::where('id', $id)->value('config_name');
    }

    public function isKeyExpired(int $keyId): bool
    {
        $expirationDate = Key::where('id', $keyId)->value('expiration_date');
        return $expirationDate <= now()->toDateTimeString();
    }
}
