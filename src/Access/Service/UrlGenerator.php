<?php

namespace App\Access\Service;

use Webmozart\Assert\Assert;

class UrlGenerator
{
    private string $baseUrl;
    public function __construct(string $baseUrl)
    {
        $this->baseUrl = rtrim($baseUrl, '/');
    }

    public function generate(string $token): string
    {
        Assert::uuid($token);
        $binaryUuid = pack('H*', str_replace('-', '', $token));
        $base64 = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($binaryUuid));

        return sprintf('%s/%s', $this->baseUrl, $base64);
    }

    public function decode(string $encodedToken): string
    {
        $base64 = str_replace(['-', '_'], ['+', '/'], $encodedToken);

        $binary = base64_decode($base64);

        $hex = bin2hex($binary);

        return sprintf(
            '%s-%s-%s-%s-%s',
            substr($hex, 0, 8),
            substr($hex, 8, 4),
            substr($hex, 12, 4),
            substr($hex, 16, 4),
            substr($hex, 20)
        );
    }
}