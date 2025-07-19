<?php

namespace App\Http\Services;

use App\Http\DTOs\StoreUserDto;
use App\Http\Repositories\UserRepository;

class UserService
{
    public function __construct(
        private readonly UserRepository $userRepository,
    ) {}

    public function createUser(StoreUserDto $dto)
    {
        $data = $dto->toArray();
        return $this->userRepository->create($data);
    }
}
