<?php

namespace Test\Functional\Access\Stream;

use App\Access\Service\UuidConverter;
use App\Product\Test\TempDir;
use Test\Functional\WebTestCase;

class RequestActionTest extends WebTestCase
{

    public function setUp(): void
    {
        parent::setUp();
        $this->loadFixtures([RequestFixture::class]);
        $this->tempDir = TempDir::create();
    }
    public function testSuccess(): void
    {
        $encodedToken = $this->getEncodedString('b035e3dc-cadc-45dd-85a1-817b6060d6fe');
        $encodedProductId = $this->getEncodedString('b38e76c0-ac23-4c48-85fd-975f32c8801f');

        $response = $this->app()->handle(self::json(
            'GET',
            '/payment-service/access/stream-pdf?token='.$encodedToken . '&productId='.$encodedProductId,
        ));

        self::assertEquals(200, $response->getStatusCode());

        self::assertEquals('application/pdf', $response->getHeaderLine('Content-Type'));

        $filename = $response->getHeaderLine('Content-Disposition');

        self::assertEquals('inline; filename="test-file"', $filename);
        self::assertFileExists('/tmp/test-file');

    }
    private function getEncodedString(string $uuid): string
    {
        return (new UuidConverter())->encode($uuid);
    }
}