<?php

declare(strict_types=1);

namespace App\Shared\Test\Unit\ValueObject;

use App\Shared\Domain\ValueObject\BaseUrl;
use PHPUnit\Framework\TestCase;

class BaseUrlTest extends TestCase
{
    public function testSuccess(): void
    {
        $baseUrl = new BaseUrl('http://example.com/');


        self::assertEquals('http://example.com', $baseUrl->getValue());
    }

    public function testEmpty(): void
    {
        self::expectException(\InvalidArgumentException::class);
        new BaseUrl('');
    }
}
