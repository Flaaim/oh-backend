<?php

declare(strict_types=1);

namespace App\Access\Test\Unit\Entity;

use App\Access\Entity\Access;
use App\Access\Entity\AccessId;
use App\Access\Entity\Email;
use App\Access\Entity\Token;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

/**
 * @internal
 */
class AccessTest extends TestCase
{
    public function testExpired(): void
    {
        $access = new Access(
            AccessId::generate(),
            'Name',
            'ot1555.1',
            new Email('test@email.ru'),
            Uuid::uuid4()->toString(),
            new Token(Uuid::uuid4()->toString(), new \DateTimeImmutable('-2 days')),
        );

        self::assertTrue($access->isExpired());
    }

    public function testIsNotExpired(): void
    {
        $access = new Access(
            AccessId::generate(),
            'Name',
            'ot1555.1',
            new Email('test@email.ru'),
            Uuid::uuid4()->toString(),
            new Token(Uuid::uuid4()->toString(), new \DateTimeImmutable('+3 days')),
        );

        self::assertFalse($access->isExpired());
    }
}
