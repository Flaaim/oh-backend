<?php

declare(strict_types=1);

namespace App\Product\Test\Entity;

use App\Product\Entity\Currency;
use App\Product\Entity\Price\Price;
use App\Shared\Domain\ValueObject\Id;
use PHPUnit\Framework\TestCase;
use Test\Functional\Payment\ProductBuilder;

/**
 * @internal
 */
final class ProductTest extends TestCase
{
    public function testSuccess(): void
    {
        $product = (new ProductBuilder())
            ->withId($id = new Id('b38e76c0-ac23-4c48-85fd-975f32c8801f'))
            ->withCipher($cipher = 'ot155.1')
            ->withPrice($price = new Price(1000.00, new Currency('RUB')))
            ->withCourse($course = 'ot155')
            ->build();

        self::assertEquals($id->getValue(), $product->getId()->getValue());
        self::assertEquals($cipher, $product->getCipher());
        self::assertEquals($price->getValue(), $product->getPrice()->getValue());
        self::assertEquals($course, $product->getCourse());
    }
}
