<?php

namespace App\Product\Command\Upload;

use Psr\Http\Message\UploadedFileInterface;
use Webmozart\Assert\Assert;

class Command
{
    public function __construct(
        public UploadedFileInterface $uploadFiles,
        public string $targetPath
    )
    {
        Assert::notEmpty($targetPath);
    }
}