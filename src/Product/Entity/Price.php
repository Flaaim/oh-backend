<?php

namespace App\Product\Entity;

use Webmozart\Assert\Assert;

class Price
{
    private float $value;
    private Currency $currency;
    public function __construct(float $value, Currency $currency)
    {
        Assert::greaterThan($value, 0);
        $this->value = round($value, 2);
        $this->currency = $currency;
    }
    public function getValue(): float
    {
        return $this->value;
    }
    public function getCurrency(): Currency
    {
        return $this->currency;
    }
    public function formatted(): string
    {
        return number_format($this->value, 2, '.', '') . $this->getCurrency()->getValue();
    }

    public function equals(Price $price): bool
    {
        return $this->value === $price->getValue();
    }

    public function withRecount(string $type): self
    {
        $value = match ($type) {
            Type::Access->value => $this->value,
            Type::File->value => round(($this->value * 1.75), 2),
            default =>  throw new \DomainException('Unsupported price type: ' . $type)
        };
        return new self($value, $this->currency);
    }
}