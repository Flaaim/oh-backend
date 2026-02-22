<?php

namespace App\Http;

use Slim\Psr7\Factory\StreamFactory;
use Slim\Psr7\Headers;
use Slim\Psr7\Response;

class FileResponse extends Response
{
    public function __construct($path, $status = 200)
    {
        if (!file_exists($path)) {
            throw new \InvalidArgumentException(
                sprintf('File not found: "%s"', $path)
            );
        }

        if (!is_file($path)) {
            throw new \InvalidArgumentException(
                sprintf('Path is not a file: "%s"', $path)
            );
        }

        if (!is_readable($path)) {
            throw new \InvalidArgumentException(
                sprintf('File is not readable: "%s"', $path)
            );
        }
        parent::__construct(
            $status,
            new Headers([
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . basename($path) . '"',
                'Content-Length' => filesize($path),
                'Accept-Ranges' => 'none',
                'Cache-Control' => 'private, no-cache, no-store, must-revalidate',
                'Pragma' => 'no-cache',
                'Expires' => '0',
                'X-Content-Type-Options' => 'nosniff',
                'Content-Security-Policy' => 'default-src \'none\'; sandbox',
            ]),
            (new StreamFactory())->createStreamFromFile($path)
        );
    }
}