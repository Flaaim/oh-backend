<?php

declare(strict_types=1);

namespace App\Payment\Test\Entity;

use App\Payment\Entity\Status;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class StatusTest extends TestCase
{
    public function testSuccess(): void
    {
        $status = new Status('succeeded');
        self::assertEquals('succeeded', $status->getValue());
    }

    public function testInvalid(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $status = new Status('invalid');
    }

    public function testPending(): void
    {
        $status = Status::pending();
        self::assertEquals('pending', $status->getValue());
    }
    public function testCancelled(): void
    {
        $status = Status::cancelled();
        self::assertEquals('cancelled', $status->getValue());
    }
    public function testSucceeded(): void
    {
        $status = Status::succeeded();
        self::assertEquals('succeeded', $status->getValue());
    }
}
