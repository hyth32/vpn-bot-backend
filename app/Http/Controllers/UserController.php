<?php

namespace App\Http\Controllers;

use App\Http\DTOs\StoreUserDto;
use App\Http\Repositories\UserRepository;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Services\UserService;

class UserController extends Controller
{
    public function __construct(
        private readonly UserService $service,
        private readonly UserRepository $repository,
    ) {}

    /**
     * @OA\Post(
     *     path="/api/v1/users",
     *     tags={"Users"},
     *     summary="Создание пользователя",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/StoreUserDto")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="User created successfully",
     *     )
     * )
     */
    public function store(StoreUserRequest $request)
    {
        $userDto = StoreUserDto::fromRequest($request->validated());
        $userExists = $this->repository->findByTelegramId($userDto->getTelegramId());
        if ($userExists) {
            abort(403, 'Пользователь существует');
        }

        $user = $this->service->createUser($userDto);
        
        if (!$user) {
            return response()->json(['error' => 'User creation failed'], 500);
        }
        
        return response()->json(['message' => 'User created successfully'], 201);
    }
}
