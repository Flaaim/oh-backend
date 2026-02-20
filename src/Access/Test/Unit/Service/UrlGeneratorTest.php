<?php

namespace App\Access\Test\Unit\Service;

use App\Access\Service\UrlGenerator;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;


class UrlGeneratorTest extends TestCase
{
    public function testSuccess(): void
    {
        $generator = new UrlGenerator('http://localhost:8080');
        $value = Uuid::uuid4()->toString();

        $url = $generator->generate($value);

        $path = parse_url($url, PHP_URL_PATH);
        $tokenPart = ltrim($path, '/');

        self::assertEquals(22, strlen($tokenPart));

        $this->assertMatchesRegularExpression(
            '/^[a-zA-Z0-9\-_]+$/',
            $tokenPart,
        );
    }

    public function testInvalid(): void
    {
        $generator = new UrlGenerator('http://localhost:8080');
        self::expectException(\InvalidArgumentException::class);
        $generator->generate('invalid');
    }

    public function testEmpty(): void
    {
        $generator = new UrlGenerator('http://localhost:8080');
        self::expectException(\InvalidArgumentException::class);
        $generator->generate('');
    }
}