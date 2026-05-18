<?php

declare(strict_types=1);

namespace App\Product\Entity\Decorator;

use App\Product\Entity\Price\PriceInterface;

abstract class PriceDecorator implements PriceInterface
{
    #[\Override]
    abstract public function getValue(): float;

}
