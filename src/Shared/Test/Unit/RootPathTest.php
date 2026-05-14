<?php

declare(strict_types=1);

namespace App\Shared\Test\Unit;

use App\Shared\Domain\ValueObject\RootPath;
use PHPUnit\Framework\TestCase;

class RootPathTest extends TestCase
{
    public function testSuccess(): void
    {
        $file = new RootPath(sys_get_temp_dir());
        $this->assertEquals(sys_get_temp_dir(), $file->getValue());
    }
    public function testEmpty(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new RootPath('');
    }
}
