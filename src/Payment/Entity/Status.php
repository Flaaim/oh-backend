<?php

declare(strict_types=1);

namespace App\Payment\Entity;

use Webmozart\Assert\Assert;

final class Status
{
    private const ALLOWED_STATUSES = [
        'pending',
        'cancelled',
        'succeeded',
    ];
    private string $value;
    public function __construct(string $value)
    {
        Assert::oneOf($value, self::ALLOWED_STATUSES);
        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }
    public static function pending(): self
    {
        return new self('pending');
    }
    public static function cancelled(): self
    {
        return new self('cancelled');
    }
    public static function succeeded(): self
    {
        return new self('succeeded');
    }
}
