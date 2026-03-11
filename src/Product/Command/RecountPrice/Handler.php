<?php

namespace App\Product\Command\RecountPrice;

use App\Flusher;
use App\Product\Entity\DTO\ProductDTO;
use App\Product\Entity\ProductRepository;
use App\Shared\Domain\ValueObject\Id;

class Handler
{
    public function __construct(
        private readonly ProductRepository $products,
        private readonly Flusher $flusher,
    ){
    }
    public function handle(Command $command): ProductDTO
    {
        $product = $this->products->get(new Id($command->productId));

        $product->recountPrice($command->type);

        $this->flusher->flush();

        return new ProductDTO(
            $product->getId()->getValue(),
            $product->getName(),
            $product->getCipher(),
            $product->getPrice()->getValue(),
        );
    }
}