<?php

namespace App\Http\DTOs;

/**
 * @OA\Schema(
 *     schema="StoreUserDto",
 *     type="object",
 *     required={"name", "telegram_id"},
 *     @OA\Property(property="name", type="string", example="John Doe"),
 *     @OA\Property(property="telegram_id", type="string", example="123456789"),
 * )
 */
class StoreUserDto
{
    public string $name;
    public string $telegramId;

    public function __construct(string $name, string $telegramId)
    {
        $this->name = $name;
        $this->telegramId = $telegramId;
    }

    public static function fromRequest(array $data): self
    {
        return new self(
            name: $data['name'],
            telegramId: $data['telegram_id'],
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'telegram_id' => $this->telegramId,
            'last_active_at' => now(),
        ];
    }
}
