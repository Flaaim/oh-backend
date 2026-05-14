<?php

declare(strict_types=1);

namespace App\Access\Entity;

use Webmozart\Assert\Assert;

final class Email
{
    private string $value;
    public function __construct(string $value)
    {
        Assert::email($value);
        $this->value = mb_strtolower($value);
    }
    public function getValue(): string
    {
        return $this->value;
    }
}
