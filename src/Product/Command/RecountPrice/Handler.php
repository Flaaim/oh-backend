<?php

declare(strict_types=1);

namespace App\Product\Command\RecountPrice;

use App\Product\Entity\DTO\ProductDTO;
use App\Product\Entity\ProductRepository;
use App\Shared\Domain\ValueObject\Id;

final class Handler
{
    public function __construct(
        private readonly ProductRepository $products,
    ) {
    }
    public function handle(Command $command): ProductDTO
    {
        $product = $this->products->get(new Id($command->productId));

        $price = $product->calculatePriceFor($command->type);

        return new ProductDTO(
            $product->getId()->getValue(),
            $product->getName(),
            $product->getCipher(),
            (string)$price->getValue(),
        );
    }
}
