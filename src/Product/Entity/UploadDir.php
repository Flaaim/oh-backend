<?php

namespace App\Product\Entity;

use App\Product\Service\ValidatePath;
use App\Shared\Domain\Service\Template\TemplatePath;

class UploadDir
{
    private string $targetPath;

    public function __construct(TemplatePath $uploadDir, string $targetPath, ValidatePath $validatePath)
    {
        $this->ensurePathValid($targetPath, $validatePath);
        $this->targetPath = $this->buildTargetPath($uploadDir, $targetPath);
    }
    public function getValue(): string
    {
        return $this->targetPath;
    }
    public function ensurePathValid(string $targetPath, ValidatePath $validatePath): void
    {
        if(!$validatePath->validate($targetPath)){
            throw new \DomainException(
                sprintf('Target path "%s" is not valid', $targetPath)
            );
        }
    }

    private function buildTargetPath(TemplatePath $uploadDir, string $targetPath): string
    {
        return rtrim($uploadDir->getValue(), DIRECTORY_SEPARATOR)
            . DIRECTORY_SEPARATOR
            . ltrim($targetPath, DIRECTORY_SEPARATOR);
    }

}