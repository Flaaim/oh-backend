<?php

namespace App\Ticket\Test\Unit\Entity;

use App\Ticket\Entity\Status;
use PHPUnit\Framework\TestCase;

class StatusTest extends TestCase
{
    public function testSuccess(): void
    {
        $active = Status::active();
        self::assertEquals('active', $active->getValue());

        $inactive = Status::inactive();
        self::assertEquals('inactive', $inactive->getValue());
    }

    public function testActive(): void
    {
        $status = Status::active();
        self::assertTrue($status->isActive());
    }

    public function testInactive(): void
    {
        $status = Status::inactive();
        self::assertFalse($status->isActive());
    }

    public function testCreate(): void
    {
        $status = Status::create($value = 'active');
        self::assertEquals($value, $status->getValue());
    }

    public function testCase(): void
    {
        self::expectException(\DomainException::class);
        self::expectExceptionMessage('Status with ACTIVE cannot be created.');
        Status::create('ACTIVE');
    }

    public function testCreateFailed(): void
    {
        self::expectException(\DomainException::class);
        self::expectExceptionMessage('Status with some_value cannot be created.');
        Status::create('some_value');
    }
}