<?php

namespace App\Product\Service;

use App\Product\Entity\UploadDir;
use FilesystemIterator;
use Psr\Http\Message\UploadedFileInterface;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RuntimeException;

class FileHandler
{
    private UploadDir $path;
    const ALLOWED_MIME_TYPES = [
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    ];
    public function __construct(UploadDir $path)
    {
        $this->path = $path;
    }

    public function handle(UploadedFileInterface $file): array
    {
        $this->createDir();
        $this->deleteFile($this->path->getValue());
        return $this->processFile($file);
    }
    private function createDir(): void
    {
        if(!is_dir($this->path->getValue())){
            $status = mkdir($this->path->getValue(), 0777, true);
            if($status === false){
                throw new RuntimeException('Unable to create directory ' . $this->path->getValue());
            }
        }
    }
    private function processFile(UploadedFileInterface $uploadedFile): array
    {
        if($uploadedFile->getError() !== UPLOAD_ERR_OK){
            throw new RuntimeException('Error uploading file '. $uploadedFile->getError());
        }

        if(!in_array($uploadedFile->getClientMediaType(), self::ALLOWED_MIME_TYPES)){
            throw new RuntimeException('Invalid file type '. $uploadedFile->getClientMediaType());
        }

        $file = $this->path->getValue() .
            DIRECTORY_SEPARATOR . $uploadedFile->getClientFilename();

        $uploadedFile->moveTo($file);

        return [
            'name' => $uploadedFile->getClientFilename(),
            'mime_type' => $uploadedFile->getClientMediaType(),
            'size' => $uploadedFile->getSize(),
            'path' => $file,
        ];
    }

    private function deleteFile(string $dir): void
    {
        if(!is_dir($dir)){
            throw new RuntimeException('Unable to delete directory. Directory not found' . $dir);
        }
        $it = new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS);
        $files = new RecursiveIteratorIterator($it, RecursiveIteratorIterator::CHILD_FIRST);
        foreach ($files as $file) {
            unlink($file->getRealPath());
        }

    }
}