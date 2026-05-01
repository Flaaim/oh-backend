<?php

namespace App\Product\Entity\DTO;

class ProductDTO
{
    public function __construct(
        public string $id,
        public string $name,
        public string $cipher,
        public string $price,
    ) {
    }
}
