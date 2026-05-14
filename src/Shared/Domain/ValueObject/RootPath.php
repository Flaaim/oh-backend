<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

use Webmozart\Assert\Assert;

class RootPath
{
    private readonly string $basePath;
    public function __construct(string $basePath)
    {
        Assert::notEmpty($basePath);
        $this->basePath = $basePath;
    }
    public function getValue(): string
    {
        return $this->basePath;
    }
}
