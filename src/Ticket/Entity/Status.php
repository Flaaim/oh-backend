<?php

namespace App\Ticket\Entity;

use DomainException;
use Webmozart\Assert\Assert;

final class Status
{
    private const ACTIVE = 'active';
    private const INACTIVE = 'inactive';
    private string $value;
    private function __construct(string $value)
    {
        Assert::oneOf($value, [self::ACTIVE, self::INACTIVE]);
        $this->value = $value;
    }
    public function getValue(): string
    {
        return $this->value;
    }
    public static function active(): self
    {
        return new self(self::ACTIVE);
    }
    public static function inactive(): self
    {
        return new self(self::INACTIVE);
    }
    public static function create(string $value): self
    {
        return match ($value) {
            self::ACTIVE, self::INACTIVE => new self(self::ACTIVE),
            default => throw new DomainException("Status with {$value} cannot be created."),
        };
    }
    public function isActive(): bool
    {
        return $this->value === self::ACTIVE;
    }
}
