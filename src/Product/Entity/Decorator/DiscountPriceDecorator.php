<?php

declare(strict_types=1);

namespace App\Product\Entity\Decorator;

use App\Product\Entity\Price\PriceInterface;

final class DiscountPriceDecorator extends PriceDecorator
{
    public const DISCOUNT = 0.95;
    public function __construct(
        private readonly PriceInterface $price,
    ) {
    }
    #[\Override]
    public function getValue(): float
    {
        return $this->price->getValue() * self::DISCOUNT;
    }
}
