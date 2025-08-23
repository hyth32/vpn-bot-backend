<?php

namespace App\Http\DTOs;

class KeyCreateDTO extends BaseDTO
{
    public function __construct(
        public int $user_id,
        public int $region_id,
        public int $period_id,
        public string $expiration_date,
        public string $config_id,
        public string $config_name,
    ) {}
}
