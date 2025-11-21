<?php

namespace App\TelegramBot\Service;

class FileHandler
{
    public function __construct(private readonly string $filePath)
    {}

    public function getFile(): string
    {
        return $this->filePath;
    }
}