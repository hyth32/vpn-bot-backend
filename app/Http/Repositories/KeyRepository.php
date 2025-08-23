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

    public function index(int $userId, int $offset, int $limit)
    {
        return Key::with('region')
            ->where('user_id', $userId)
            ->offset($offset)
            ->limit($limit)
            ->get();
    }

    public function findOne(int $id): Key
    {
        return Key::with('region')->find($id);
    }

    public function deleteByConfigId(string $configId)
    {
        return Key::where('config_id', $configId)->delete();
    }

    public function countByUserId(int $userId): int
    {
        return Key::where('user_id', $userId)->count();
    }

    public function existsByUserId(int $userId, int $keyId): bool
    {
        return Key::where('id', $keyId)->where('user_id', $userId)->exists();
    }

    public function getConfigId(int $keyId): ?string
    {
        return Key::where('id', $keyId)->value('config_id');
    }

    public function isKeyExpired(int $keyId): bool
    {
        $expirationDate = Key::where('id', $keyId)->value('expiration_date');
        return $expirationDate <= now()->toDateTimeString();
    }
}
