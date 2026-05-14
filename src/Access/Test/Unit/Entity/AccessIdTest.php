<?php

declare(strict_types=1);

namespace App\Access\Test\Unit\Entity;

use App\Shared\Domain\ValueObject\Id;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

/**
 * @internal
 */
final class AccessIdTest extends TestCase
{
    public function testSuccess(): void
    {
        $id = new Id($value = Uuid::uuid4()->toString());

        self::assertSame($value, $id->getValue());
    }

    public function testInvalid(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new Id('invalid');
    }

    public function testEmpty(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new Id('');
    }
    public function testCase(): void
    {
        $value = Uuid::uuid4()->toString();
        $id = new Id(mb_strtoupper($value));

        self::assertSame($value, $id->getValue());
    }
}
