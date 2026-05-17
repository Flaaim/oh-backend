<?php

declare(strict_types=1);

namespace App\Product\Entity\Decorator;

use App\Product\Entity\Currency;
use App\Product\Entity\Price\PriceInterface;

abstract class PriceDecorator implements PriceInterface
{
    public function __construct(
        private readonly PriceInterface $price,
    ) {
    }
    #[\Override]
     public function getValue(): float
    {
        return $this->price->getValue();
    }

    public function getCurrency(): Currency
    {
        return $this->price->getCurrency();
    }
}
