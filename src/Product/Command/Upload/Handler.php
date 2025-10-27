<?php

namespace App\Product\Command\Upload;

use App\Product\Entity\UploadDir;
use App\Product\Service\FileHandler;
use App\Product\Service\ValidatePath;
use App\Shared\Domain\Service\Template\TemplatePath;

class Handler
{
    public function __construct(
        private readonly TemplatePath $templatePath,
        private readonly ValidatePath $validatePath
    )
    {}

    public function handle(Command $command): Response
    {
        $fileHandler = new FileHandler(
            new UploadDir($this->templatePath, $command->targetPath, $this->validatePath)
        );

        $result = $fileHandler->handle($command->uploadFiles);

        return Response::fromArray($result);
    }
}