<?php

namespace App\Product\Command\Upsert;

use App\Flusher;
use App\Http\EmptyResponse;
use App\Product\Entity\Currency;
use App\Product\Entity\File;
use App\Product\Entity\Price;
use App\Product\Entity\Product;
use App\Product\Entity\ProductRepository;
use App\Shared\ValueObject\Id;
use Ramsey\Uuid\Uuid;

class Handler
{
    public function __construct(
        private readonly ProductRepository $products,
        private readonly Flusher $flusher,
    )
    {}
    public function handle(Command $command): void
    {
        $product = $this->products->findByCourse($command->course);

        if($product) {
            /** @var Product $product */
            $product->update(
                $command->name,
                new Price($command->amount, new Currency('RUB')),
                new File($command->path),
                $product->getCipher(),
            );
        }else{
            $product = new Product(
                new Id(Uuid::uuid4()->toString()),
                $command->name,
                new Price($command->amount, new Currency('RUB')),
                new File($command->path),
                $command->cipher,
                $command->course
            );
        }

        $this->products->upsert($product);

        $this->flusher->flush();

    }
}