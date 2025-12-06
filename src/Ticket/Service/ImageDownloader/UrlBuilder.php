<?php

namespace App\Ticket\Service\ImageDownloader;

class UrlBuilder
{
    private string $baseUrl;

    public function __construct(string $baseUrl)
    {
        $this->baseUrl = $baseUrl;
    }
    public function buildNewQuestionUrl(string $imagePath): string
    {
        $position = strpos($imagePath, '/QuestionImages');
        if ($position !== false) {
            $result = substr($imagePath, $position + strlen('/QuestionImages'));
            return $this->baseUrl . $result;
        }
        return $this->baseUrl;
    }
}
