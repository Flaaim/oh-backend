<?php

namespace App\Access\Service;

use Webmozart\Assert\Assert;

class UuidConverter
{
    public function encode(string $uuid): string
    {
        Assert::uuid($uuid);

        $binaryUuid = hex2bin(str_replace('-', '', $uuid));

        $base64 = base64_encode($binaryUuid);

        return rtrim(strtr($base64, '+/', '-_'), '=');
    }

    public function decode(string $url): string
    {
        $base64 = strtr($url, '-_', '+/');

        $base64 = str_pad($base64, ceil(strlen($base64) / 4) * 4, '=');

        $binary = base64_decode($base64, true);

        if ($binary === false || strlen($binary) !== 16) {
            throw new \InvalidArgumentException('Invalid token format');
        }

        $hex = bin2hex($binary);

        return vsprintf('%08s-%04s-%04s-%04s-%012s', [
            substr($hex, 0, 8),
            substr($hex, 8, 4),
            substr($hex, 12, 4),
            substr($hex, 16, 4),
            substr($hex, 20, 12)
        ]);
    }
}