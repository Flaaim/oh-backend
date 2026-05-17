<?php

declare(strict_types=1);

namespace App\Product\Entity\Price;

use App\Product\Entity\Currency;

interface PriceInterface
{
    public function getValue(): float;
    public function getCurrency(): Currency;
}