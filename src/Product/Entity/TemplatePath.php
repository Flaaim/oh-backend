<?php

namespace App\Product\Entity;

use Webmozart\Assert\Assert;

class TemplatePath
{
    private readonly string $basePath;
    public function __construct(string $basePath)
    {
        Assert::notEmpty($basePath);
        $this->basePath = $basePath;
    }
    public function getBasePath(): string
    {
        return $this->basePath;
    }
}