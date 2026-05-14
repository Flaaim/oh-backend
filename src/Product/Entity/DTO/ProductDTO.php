<?php

declare(strict_types=1);

namespace App\Product\Entity\DTO;

final class ProductDTO
{
    public function __construct(
        public string $id,
        public string $name,
        public string $cipher,
        public string $price,
    ) {
    }
}
