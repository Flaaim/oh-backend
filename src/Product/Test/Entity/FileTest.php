<?php

namespace App\Product\Test\Entity;

use App\Product\Entity\File;
use App\Shared\Domain\TemplatePath;
use PHPUnit\Framework\TestCase;

class FileTest extends TestCase
{
    public function testSuccess(): void
    {
        $file = new File(
            $this->getTemplatePath(),
            '/ppe/template.rar'
        );
        $this->assertEquals('/tmp/ppe/template.rar', $file->getPathToFile());
    }
    public function testEmpty(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new File(
            $this->getTemplatePath(),
            ''
        );
    }
    private function getTemplatePath(): TemplatePath
    {
        return new TemplatePath(sys_get_temp_dir());
    }
}