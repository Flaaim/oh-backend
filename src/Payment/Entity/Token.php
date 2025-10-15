<?php

namespace App\Payment\Entity;

use DateTimeImmutable;
use Webmozart\Assert\Assert;

class Token
{
    private string $value;
    private DateTimeImmutable $expired;
    public function __construct(string $value, DateTimeImmutable $expired)
    {
        Assert::uuid($value);
        $this->value = mb_strtolower($value);
        $this->expired = $expired;
    }

    public function getValue(): string
    {
        return $this->value;
    }
    public function getExpired(): DateTimeImmutable
    {
        return $this->expired;
    }

    public function validate(string $value, DateTimeImmutable $date): void
    {
        if(!$this->isEqualTo($value)) {
            throw new \DomainException('Token is invalid.');
        }
        if($this->isExpiredTo($date)) {
            throw new \DomainException('Token is expired.');
        }
    }
    public function isEqualTo(string $value): bool
    {
        return $this->value === $value;
    }
    public function isExpiredTo(DateTimeImmutable $date): bool
    {
        return $this->expired <= $date;
    }
}