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
}
