<?php

namespace App\Product\Test\Command\RecountPrice;

use App\Flusher;
use App\Product\Command\RecountPrice\Command;
use App\Product\Command\RecountPrice\Handler;
use App\Product\Entity\Currency;
use App\Product\Entity\Price;
use App\Product\Entity\ProductRepository;
use App\Shared\Domain\ValueObject\Id;
use PHPUnit\Framework\TestCase;
use Test\Functional\Payment\ProductBuilder;

class RecountPriceTest extends TestCase
{
    public function testRecountAccess(): void
    {
        $command = new Command(
            'access',
            '0a1e58cb-b7fd-4f46-aa62-911ad8257c84'
        );
        $product = (new ProductBuilder())->withPrice(new Price(150, new Currency('RUB')))->build();
        $products = $this->createMock(ProductRepository::class);

        $productId = new Id('0a1e58cb-b7fd-4f46-aa62-911ad8257c84');
        $products->expects(self::once())->method('get')
            ->with($this->equalTo($productId))
            ->willReturn($product);


        $handler = new Handler($products);

        $response = $handler->handle($command);

        self::assertEquals('Оказание первой помощи пострадавшим', $response->name);
        self::assertEquals('ОТ 201.18', $response->cipher);
        self::assertEquals(150.00, $response->price);
    }

    public function testRecountFile(): void
    {
        $command = new Command(
            'file',
            '0a1e58cb-b7fd-4f46-aa62-911ad8257c84'
        );
        $product = (new ProductBuilder())->withPrice(new Price(150, new Currency('RUB')))->build();
        $products = $this->createMock(ProductRepository::class);

        $productId = new Id('0a1e58cb-b7fd-4f46-aa62-911ad8257c84');
        $products->expects(self::once())->method('get')
            ->with($this->equalTo($productId))
            ->willReturn($product);


        $handler = new Handler($products);

        $response = $handler->handle($command);

        self::assertEquals('Оказание первой помощи пострадавшим', $response->name);
        self::assertEquals('ОТ 201.18', $response->cipher);
        self::assertEquals(262.50, $response->price);
    }
}