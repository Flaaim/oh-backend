<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

use Webmozart\Assert\Assert;

class BaseUrl
{
    public function __construct(
        private string $url,
    ) {
        Assert::notEmpty($this->url);
        $this->url = rtrim($this->url, '/');
    }

    public function getValue(): string
    {
        return $this->url;
    }
}
