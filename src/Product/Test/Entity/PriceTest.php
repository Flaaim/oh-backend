<?php

declare(strict_types=1);

namespace App\Product\Test\Entity;

use App\Product\Entity\Currency;
use App\Product\Entity\Price;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class PriceTest extends TestCase
{
    public function testSuccess(): void
    {
        $price = new Price(150.00, new Currency('RUB'));
        self::assertEquals(150.00, $price->getValue());
    }
    public function testInvalid(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new Price(0.00, new Currency('RUB'));
    }
    public function testRound(): void
    {
        $price = new Price(150.00000, new Currency('RUB'));
        self::assertEquals(150.00, $price->getValue());
    }

    public function testEquals(): void
    {
        $price = new Price(150.00, new Currency('RUB'));
        $newPrice = new Price(150.00, new Currency('RUB'));
        self::assertTrue($price->equals($newPrice));
    }

    public function testRecountNoChanges(): void
    {
        $price = new Price(150.00, new Currency('RUB'));

        $newPrice = $price->withRecount('access');
        self::assertEquals(150.00, $newPrice->getValue());
    }

    public function testRecount(): void
    {
        $price = new Price(150.00, new Currency('RUB'));
        $newPrice = $price->withRecount('file');

        self::assertEquals(262.5, $newPrice->getValue());
    }
}
