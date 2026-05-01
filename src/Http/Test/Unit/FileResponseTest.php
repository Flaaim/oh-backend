<?php

namespace App\Http\Test\Unit;

use App\Http\FileResponse;
use App\Product\Test\TempDir;
use PHPUnit\Framework\TestCase;

class FileResponseTest extends TestCase
{
    private TempDir $tempDir;
    protected function setUp(): void
    {
        $this->tempDir = TempDir::create();
    }
    public function testDefault(): void
    {
        $response = new FileResponse($file = $this->tempFile());

        self::assertEquals(200, $response->getStatusCode());
        self::assertTrue($response->hasHeader('Content-Type'));

        self::assertEquals('application/pdf', $response->getHeaderLine('Content-Type'));


        self::assertEquals('inline; filename="' . basename($file) . '"', $response->getHeaderLine('Content-Disposition'));

        self::assertTrue($response->hasHeader('X-Content-Type-Options'));
        self::assertEquals('nosniff', $response->getHeaderLine('X-Content-Type-Options'));

        $body = $response->getBody();
        self::assertFalse($body->isWritable());
        self::assertTrue($body->isReadable());
        self::assertTrue($body->isSeekable());

        $body->rewind();
        $content = $body->getContents();
        self::assertEquals('%PDF-1.4 test content', $content);
    }

    public function testNotFound(): void
    {
        self::expectException(\InvalidArgumentException::class);
        new FileResponse('/file-not-found.pdf');
    }
    protected function tearDown(): void
    {
        $this->tempDir->clear();
    }
    private function tempFile(): string
    {
        $file = tempnam($this->tempDir->getValue(), 'pdf_test_');
        $result = file_put_contents($file, '%PDF-1.4 test content');
        if (!$result) {
            throw new \RuntimeException('Failed to write temp file');
        }
        return $file;
    }
}
