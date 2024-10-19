<?php

namespace App\Http\DTOs;

class CreateOrderDTO
{
    public function __construct(
        public array $products
    ) {
    }
}
