<?php

namespace App\Http\Repositories;

use App\Models\User;

class UserRepository
{
    public function create(array $data): User
    {
        return User::create($data);
    }

    public function findByTelegramId(string $telegramId): ?User
    {
        return User::where('telegram_id', $telegramId)->first();
    }

    private function getColumnByTelegramId(string $telegramId, string $column)
    {
        return User::where('telegram_id', $telegramId)->value($column);
    }

    public function getIdFromTelegramId(string $telegramId): ?int
    {
        return $this->getColumnByTelegramId($telegramId, 'id');
    }

    public function getNameFromTelegramId(string $telegramId): ?string
    {
        return $this->getColumnByTelegramId($telegramId, 'name');
    }

    public function hasUsedFreeKey(string $telegramId): bool
    {
        return $this->getColumnByTelegramId($telegramId, 'free_key_used');
    }

    public function markFreeKeyUsed(string $telegramId): int
    {
        return User::where('telegram_id', $telegramId)->update(['free_key_used' => true]);
    }
}
