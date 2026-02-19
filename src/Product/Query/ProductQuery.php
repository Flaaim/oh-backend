<?php

namespace App\Product\Query;

use App\Product\Entity\Product;
use App\Product\Entity\ProductRepository;
use App\Shared\Domain\ProductQuery\ProductQueryInterface;
use App\Shared\Domain\ValueObject\Id;

class ProductQuery implements ProductQueryInterface
{
    public function __construct(
        private readonly ProductRepository $products
    ){
    }
    public function getProduct(string $productId): Product
    {
        return $this->products->get(new Id($productId));
    }
}