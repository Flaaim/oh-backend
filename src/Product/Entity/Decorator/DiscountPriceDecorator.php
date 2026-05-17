<?php

declare(strict_types=1);

namespace App\Product\Entity\Decorator;

final class DiscountPriceDecorator extends PriceDecorator
{
    public const DISCOUNT = 0.95;

    public function getValue(): float
    {
        return parent::getValue() * self::DISCOUNT;
    }
}
