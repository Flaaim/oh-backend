<?php

declare(strict_types=1);

namespace App\Payment\Test\Entity;

use App\Payment\Entity\Token;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

/**
 * @internal
 */
class TokenTest extends TestCase
{
    public function testSuccess(): void
    {
        $value = Uuid::uuid4()->toString();
        $token = new Token(
            $value,
            $expired = new DateTimeImmutable('+ 1 hour'),
        );

        self::assertEquals($value, $token->getValue());
        self::assertEquals($expired, $token->getExpired());
    }

    public function testCase(): void
    {
        $value = Uuid::uuid4()->toString();
        $token = new Token(mb_strtoupper($value), new DateTimeImmutable('+ 1 hour'));

        self::assertEquals($value, $token->getValue());
    }

    public function testInvalid(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new Token('invalidString', new DateTimeImmutable('+ 1 hour'));
    }
    public function testExpired(): void
    {
        $value = Uuid::uuid4()->toString();
        $token = new Token(
            $value,
            new DateTimeImmutable('+ 1 hour'),
        );
        $date = new DateTimeImmutable('now');

        self::assertFalse($token->isExpiredTo($date));

        $date = new DateTimeImmutable('+ 2 hour');

        self::assertTrue($token->isExpiredTo($date));
    }

    public function testEquals(): void
    {
        $token = new Token(
            Uuid::uuid4()->toString(),
            new DateTimeImmutable('+ 1 hour'),
        );

        $newToken = new Token(
            Uuid::uuid4()->toString(),
            new DateTimeImmutable('+ 1 hour'),
        );

        self::assertFalse($token->isEqualTo($newToken->getValue()));
        self::assertTrue($token->isEqualTo($token->getValue()));
    }

    public function testValidateInvalid(): void
    {

        $token = new Token(
            Uuid::uuid4()->toString(),
            new DateTimeImmutable('+ 1 hour'),
        );

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Token is invalid.');

        $token->validate(Uuid::uuid4()->toString(), new DateTimeImmutable('now'));
    }

    public function testValidateExpired(): void
    {
        $value = Uuid::uuid4()->toString();
        $token = new Token(
            $value,
            new DateTimeImmutable('+ 1 hour'),
        );
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Token is expired.');

        $token->validate($value, new DateTimeImmutable('+ 2 hour'));
    }
}
