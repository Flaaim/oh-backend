<?php

declare(strict_types=1);

namespace App\Shared\Domain\ProductQuery;

interface ProductQueryInterface
{
    public function getProduct(string $productId): ProductQueryDTO;
}
