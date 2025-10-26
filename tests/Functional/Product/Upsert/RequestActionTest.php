<?php

namespace Test\Functional\Product\Upsert;

use DMS\PHPUnitExtensions\ArraySubset\ArraySubsetAsserts;
use Test\Functional\Json;
use Test\Functional\WebTestCase;

class RequestActionTest extends WebTestCase
{
    use ArraySubsetAsserts;
    public function setUp(): void
    {
        parent::setUp();
        $this->loadFixtures([
            RequestFixture::class,
        ]);
    }

    public function testAddProduct(): void
    {
        $response = $this->app()->handle(self::json('POST','/payment-service/products/upsert', [
            'name' => 'ПИ 1792.9 Итоговое тестирование по Программе IIП',
            'cipher' => 'ПИ 1792.9',
            'amount' => 500.00,
            'path' => 'fire/1792/pi1792.9.docx',
            'course' => '1792'
        ]));

        self::assertEquals(201, $response->getStatusCode());
        self::assertEquals('', (string)$response->getBody());
    }

    public function testUpdateProduct(): void
    {
        $response = $this->app()->handle(self::json('POST','/payment-service/products/upsert', [
            'name' => 'ПИ 1791.11 Итоговое тестирование по Программе IП',
            'cipher' => 'ПИ 1791.11',
            'amount' => 500.00,
            'path' => 'fire/1791/pi1791.11.docx',
            'course' => '1791'
        ]));

        self::assertEquals(201, $response->getStatusCode());
        self::assertEquals('', (string)$response->getBody());
    }
    public function testEmpty(): void
    {
        $response = $this->app()->handle(self::json('POST','/payment-service/products/upsert', []));

        self::assertEquals(500, $response->getStatusCode());
        self::assertJson($body = (string)$response->getBody());

        $data = Json::decode($body);
        self::assertArraySubset([
            'message' => 'Invalid request body'
        ], $data);

    }
}