<?php

namespace App\Ticket\Service\ImageDownloader;

use FilesystemIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class PathManager
{
    private string $basePath;
    private string $currentPath;
    private string $ticketPath;
    private string $questionPath;
    public function __construct(string $basePath)
    {
        $this->basePath = rtrim($basePath, DIRECTORY_SEPARATOR);
    }
    public function forTicket(string $ticketId): self
    {
        $this->currentPath = $this->basePath . DIRECTORY_SEPARATOR . $ticketId;
        $this->ticketPath = $this->currentPath;
        return $this;
    }
    public function forQuestion(string $questionId): self
    {
        if (empty($this->ticketPath)) {
            throw new \DomainException('Call forQuestion() before forTicket');
        }
        $this->currentPath = $this->ticketPath . DIRECTORY_SEPARATOR . $questionId;
        $this->questionPath = $this->currentPath;
        return $this;
    }
    public function forAnswer(string $answerId): self
    {
        if (empty($this->questionPath)) {
            throw new \DomainException('Call forAnswer() before forQuestion()');
        }
        $this->currentPath = $this->questionPath . DIRECTORY_SEPARATOR . $answerId;
        return $this;
    }
    public function create(): void
    {
        if(empty($this->ticketPath)) {
            throw new \DomainException('Call create() before forTicket');
        }
        if (!is_dir($this->currentPath)) {
            mkdir($this->currentPath, 0777, true);
        }
    }
    public function getImagePath(string $filename): string
    {
        return $this->currentPath . DIRECTORY_SEPARATOR . trim(basename($filename), DIRECTORY_SEPARATOR);
    }
    public function deleteDirectory(string $dir): bool
    {
        if (!is_dir($dir)) {
            return false;
        }

        $basePath = realpath($this->basePath);
        $targetPath = realpath($dir);

        if (!$targetPath || !str_starts_with($targetPath, $basePath)) {
            throw new \InvalidArgumentException("Cannot delete directory outside base path: $dir");
        }

        $it = new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS);
        $files = new RecursiveIteratorIterator($it,
            RecursiveIteratorIterator::CHILD_FIRST);
        foreach ($files as $file) {
            if ($file->isDir()){
                rmdir($file->getRealPath());
            } else {
                unlink($file->getRealPath());
            }
        }

        return rmdir($dir);
    }
    public function deleteTicket(string $ticketId): bool
    {
        $path = $this->basePath . DIRECTORY_SEPARATOR . $ticketId;
        return $this->deleteDirectory($path);
    }
    public function deleteQuestion(string $questionId): bool
    {
        if(empty($this->ticketPath)) {
            throw new \DomainException('Call forTicket() first');
        }
        $path = $this->ticketPath . DIRECTORY_SEPARATOR . $questionId;
        return $this->deleteDirectory($path);
    }
    public function deleteAnswer(string $answerId): bool
    {
        if(empty($this->questionPath)) {
            throw new \DomainException('Call forQuestion() first');
        }
        $path = $this->questionPath . DIRECTORY_SEPARATOR . $answerId;
        return $this->deleteDirectory($path);
    }
}
