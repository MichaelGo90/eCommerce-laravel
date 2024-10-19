<?php

namespace App\Http\DTOs;

class ListProductsDTO
{
    public function __construct(
        public int $page = 1,
        public int $perPage = 10,
        public ?string $name = null,
        public ?float $minPrice = null,
        public ?float $maxPrice = null
    ) {}
}
