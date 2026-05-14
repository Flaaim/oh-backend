<?php

declare(strict_types=1);

namespace App\Product\Service;

use App\Product\Entity\UploadDir;
use DomainException;
use FilesystemIterator;
use Psr\Http\Message\UploadedFileInterface;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

final class FileHandler
{
    private UploadDir $path;
    public function __construct(UploadDir $path)
    {
        $this->path = $path;
    }

    public function handle(UploadedFileInterface $file): void
    {
        $this->createDir();
        $this->deleteFile($this->path->getValue());
        $this->processFile($file);
    }
    private function createDir(): void
    {
        if (!is_dir($this->path->getValue())) {
            $status = mkdir($this->path->getValue(), 0o777, true);
            if ($status === false) {
                throw new \DomainException('Unable to create directory ' . $this->path->getValue());
            }
        }
    }
    private function processFile(UploadedFileInterface $uploadedFile): void
    {
        if ($uploadedFile->getError() !== UPLOAD_ERR_OK) {
            throw new \DomainException('Error uploading file ' . $uploadedFile->getError());
        }

        $file = $this->path->getValue() .
            DIRECTORY_SEPARATOR . $uploadedFile->getClientFilename();

        $uploadedFile->moveTo($file);
    }

    private function deleteFile(string $dir): void
    {
        if (!is_dir($dir)) {
            throw new DomainException('Unable to delete directory. Directory not found' . $dir);
        }
        $it = new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS);
        $files = new RecursiveIteratorIterator($it, RecursiveIteratorIterator::CHILD_FIRST);
        foreach ($files as $file) {
            unlink($file->getRealPath());
        }
    }
}
