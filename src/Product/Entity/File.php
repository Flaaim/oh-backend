<?php

namespace App\Product\Entity;

use App\Shared\Domain\TemplatePath;
use Webmozart\Assert\Assert;

class File
{
    private TemplatePath $templatePath;
    private string $pathToFile;
    public function __construct(TemplatePath $templatePath, string $pathToFile)
    {
        $this->templatePath = $templatePath;
        Assert::notEmpty($pathToFile);
        $this->pathToFile = $this->templatePath->getValue() . DIRECTORY_SEPARATOR . ltrim($pathToFile, '/');
    }

    public function getPathToFile(): string
    {
        return $this->pathToFile;
    }
}