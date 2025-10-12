<?php

namespace App\Product\Test\Entity;

use App\Product\Entity\TemplatePath;
use PHPUnit\Framework\TestCase;

class TemplatePathTest extends TestCase
{
    public function testSuccess(): void
    {
        $file = new TemplatePath(sys_get_temp_dir());
        $this->assertEquals(sys_get_temp_dir(), $file->getBasePath());
    }
    public function testEmpty(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new TemplatePath('');
    }
}