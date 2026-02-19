<?php

namespace App\Shared\Domain\ProductQuery;

interface ProductQueryInterface
{
    public function getProduct(string $productId): ProductQueryDTO;
}