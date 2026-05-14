<?php

declare(strict_types=1);

namespace App\Product\Query;

use App\Product\Entity\ProductRepository;
use App\Shared\Domain\ProductQuery\ProductQueryDTO;
use App\Shared\Domain\ProductQuery\ProductQueryInterface;
use App\Shared\Domain\ValueObject\Id;

class ProductQuery implements ProductQueryInterface
{
    public function __construct(
        private readonly ProductRepository $products
    ) {
    }
    public function getProduct(string $productId): ProductQueryDTO
    {
        $product = $this->products->get(new Id($productId));

        return new ProductQueryDTO(
            $product->getId()->getValue(),
            $product->getName(),
            $product->getCipher(),
            $product->getFile()->getValue(),
        );
    }
}
