<?php

namespace App\Http\DTOs;

class PaginationDTO
{
    public function __construct(
        public readonly int $offset,
        public readonly int $limit,
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            offset: $data['offset'] ?? 0,
            limit: $data['limit'] ?? config('app.limit_records_on_page'),
        );
    }
} 