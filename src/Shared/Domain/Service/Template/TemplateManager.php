<?php

namespace App\Shared\Domain\Service\Template;

use App\Product\Entity\File;


class TemplateManager
{
    private string $templateFile;
    public function __construct(
        private readonly TemplatePath $templatePath,
        private readonly File $file)
    {
        $this->templateFile =
            $this->templatePath->getValue() .
            DIRECTORY_SEPARATOR .
            $this->file->getPathToFile();
    }

    private function templateExists(): bool
    {
        return file_exists($this->templateFile);
    }

    public function getTemplate(): string
    {
        if (!$this->templateExists()) {
            throw new \DomainException('Template files not exists');
        }
        return $this->templateFile;
    }

}