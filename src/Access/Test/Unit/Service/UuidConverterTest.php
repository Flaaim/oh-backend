<?php

declare(strict_types=1);

namespace App\Access\Test\Unit\Service;

use App\Access\Service\UuidConverter;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

/**
 * @internal
 */
final class UuidConverterTest extends TestCase
{
    public function testConvert(): void
    {
        $converter = new UuidConverter();
        $url = $converter->encode(Uuid::uuid4()->toString());

        self::assertEquals(22, strlen($url));
        self::assertMatchesRegularExpression(
            '/^[a-zA-Z0-9\-_]+$/',
            $url,
        );
    }
    public function testEmpty(): void
    {
        self::expectException(\InvalidArgumentException::class);
        $converter = new UuidConverter();
        $converter->encode('');
    }

    public function testInvalidUuid(): void
    {
        self::expectException(\InvalidArgumentException::class);
        $converter = new UuidConverter();
        $converter->encode('invalid-uuid');
    }
    public function testDecode(): void
    {
        $converter = new UuidConverter();
        $url = $converter->encode($uuid = Uuid::uuid4()->toString());

        $decoded = $converter->decode($url);

        self::assertEquals($uuid, $decoded);
    }
    public function testCase(): void
    {
        $converter = new UuidConverter();
        $uuid = strtoupper(Uuid::uuid4()->toString());
        $url = $converter->encode($uuid);

        $decoded = $converter->decode($url);
        self::assertEquals(strtolower($uuid), $decoded);
    }
}
