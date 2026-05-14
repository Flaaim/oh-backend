<?php

declare(strict_types=1);

namespace App\Shared\Domain\ProductQuery;

class ProductQueryDTO
{
    public function __construct(
        public string $id,
        public string $name,
        public string $cipher,
        public string $file
    ) {
    }
}
