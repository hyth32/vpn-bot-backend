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
class StoreUserDto extends BaseDTO
{
    public function __construct(
        public string $name,
        public string $telegram_id,
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            name: $data['name'],
            telegram_id: $data['telegram_id'],
        );
    }

    public function toArray(): array
    {
        $data = parent::toArray();
        $data['last_active_at'] = now();
        return $data;
    }

    public function getTelegramId(): string
    {
        return $this->telegram_id;
    }
}
