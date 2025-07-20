<?php

namespace App\Http\Repositories;

use App\Models\Key;

class KeyRepository
{
    public function create(array $data)
    {
        try {
            $key = Key::create($data);
            return $key;
        } catch (\Exception $e) {
            throw new \RuntimeException('Failed to create key: ' . $e->getMessage());
        }
    }

    public function index(int $userId, int $offset, int $limit)
    {
        return Key::with('region')
            ->where('user_id', $userId)
            ->offset($offset)
            ->limit($limit)
            ->get();
    }

    public function findOne(int $id)
    {
        return Key::with('region')->find($id);
    }
}
