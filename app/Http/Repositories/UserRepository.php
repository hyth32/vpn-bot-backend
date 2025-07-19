<?php

namespace App\Http\Repositories;

use App\Models\User;

class UserRepository
{
    public function create(array $data): User
    {
        try {
            $user = User::create($data);
            return $user;
        } catch (\Exception $e) {
            throw new \RuntimeException('Failed to create user: ' . $e->getMessage());
        }
    }
}
