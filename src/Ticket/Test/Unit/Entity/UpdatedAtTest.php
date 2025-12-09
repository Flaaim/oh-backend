<?php

namespace App\Ticket\Test\Unit\Entity;

use App\Ticket\Entity\UpdatedAt;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class UpdatedAtTest extends TestCase
{
    public function testSuccess(): void
    {
        $updatedAt = new UpdatedAt('12.11.2025');
        self::assertEquals('12.11.2025', $updatedAt->format('d.m.Y'));
        self::assertInstanceOf(\DateTimeImmutable::class, $updatedAt->getValue());
    }
    public function testTrim(): void
    {
        $updatedAt = new UpdatedAt(' 12.11.2025 ');
        self::assertEquals('12.11.2025', $updatedAt->format('d.m.Y'));
    }
    #[dataProvider('dateFormatProvider')]
    public function testInvalid($value): void
    {
        self::expectException(\DomainException::class);
        new UpdatedAt($value);
    }
    public function testFormat(): void
    {
        $updatedAt = new UpdatedAt('12.11.2025');
        self::assertEquals('12.11.2025', $updatedAt->format('d.m.Y'));
    }
    public static function dateFormatProvider(): array
    {
        return [
            ['12.11. 2025'],
            ['12-11-2025'],
            ['12/11/2025'],
            ['12..11.2025'],
            ['12.11.2025г'],
            ['12.14.2025'],
        ];
    }
}