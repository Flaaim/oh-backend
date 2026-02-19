<?php

namespace App\Shared\Domain\ProductQuery;

use App\Product\Entity\Product;

interface ProductQueryInterface
{
    public function getProduct(string $productId): Product;
}