<?php

namespace App\Product\Command\Upload;

use App\Http\Validator\SlimUploadedFile as SlimUploadedFileAssert;
use Psr\Http\Message\UploadedFileInterface;
use Symfony\Component\Validator\Constraints as Assert;

class Command
{
    public function __construct(
        #[Assert\NotNull(message: 'Upload file required.')]
        #[SlimUploadedFileAssert(
            maxSize: '15M',
            mimeTypes: [
                'application/msword',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            ],
            extensions: [
                'docx',
                'doc'
            ]
        )]
        public ?UploadedFileInterface $uploadFile,
        #[Assert\NotBlank]
        public string $targetPath
    )
    {}
}