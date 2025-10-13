<?php

namespace App\Shared\Test\Unit;

use App\Product\Entity\File;
use App\Shared\Domain\Service\Template\TemplateManager;
use App\Shared\Domain\Service\Template\TemplatePath;
use PHPUnit\Framework\TestCase;

class TemplateManagerTest extends TestCase
{
    public function testSuccess(): void
    {
        $tempFile = tempnam(sys_get_temp_dir(), 'template');

        $manager = new TemplateManager(
            new TemplatePath(sys_get_temp_dir()),
            new File(basename($tempFile))
        );
        $file = sys_get_temp_dir() . DIRECTORY_SEPARATOR . basename($tempFile);
        $this->assertEquals($file, $manager->getTemplate());
        $this->assertFileExists($file);
        unlink($tempFile);
    }

    public function testSlash(): void
    {
        $tempFile = tempnam(sys_get_temp_dir(), 'template');

        $manager = new TemplateManager(
            new TemplatePath(sys_get_temp_dir() . '/'),
            new File('/' . basename($tempFile))
        );
        $file = sys_get_temp_dir() . DIRECTORY_SEPARATOR . basename($tempFile);
        $this->assertEquals($file, $manager->getTemplate());
        $this->assertFileExists($file);
        unlink($tempFile);
    }

    public function testFileNotFound(): void
    {
        $manager = new TemplateManager(
            new TemplatePath(sys_get_temp_dir()),
            new File('/tmp/file.txt')
        );
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Template files not exists');
        $manager->getTemplate();

    }

}