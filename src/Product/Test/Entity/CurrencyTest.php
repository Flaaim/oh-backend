<?php

declare(strict_types=1);

namespace App\Product\Test\Entity;

use App\Product\Entity\Currency;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class CurrencyTest extends TestCase
{
    public function testSuccess(): void
    {
        $currency = new Currency('RUB');
        self::assertEquals('RUB', $currency->getValue());
    }

    public function testInvalid(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new Currency('RKZ');
    }

    public function testEmpty(): void
    {
        $currency = new Currency();
        self::assertEquals('RUB', $currency->getValue());
    }
}
