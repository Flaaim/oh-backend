<?php

namespace Test\Functional\Product\Get;

use Test\Functional\Json;
use Test\Functional\WebTestCase;

class RequestActionTest extends WebTestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->loadFixtures([RequestFixture::class]);
    }

    public function testSuccess(): void
    {
        $response = $this->app()->handle(self::json('GET', '/payment-service/products/get?productId=b38e76c0-ac23-4c48-85fd-975f32c8801f'));

        $this->assertEquals(200, $response->getStatusCode());

        self::assertJson($body = (string)$response->getBody());
        $data = Json::decode($body);

        self::assertEquals([
            'id' => 'b38e76c0-ac23-4c48-85fd-975f32c8801f',
            'name' => 'ПИ 1791.10 Итоговое тестирование по Программе IП',
            'cipher' => 'ПИ 1791.10',
            'price' => '550.00RUB',
        ], $data);
    }

    public function testEmpty(): void
    {
        $response = $this->app()->handle(self::json('GET', '/payment-service/products/get?productId='));

        $this->assertEquals(422, $response->getStatusCode());

        $body = (string)$response->getBody();
        $data = Json::decode($body);
        self::assertEquals([
            'errors' => [
                'productId' => 'This value should not be blank.'
            ]
        ], $data);
    }
    public function testInvalid(): void
    {
        $response = $this->app()->handle(self::json('GET', '/payment-service/products/get?productId=invalid'));

        $this->assertEquals(422, $response->getStatusCode());
        $body = (string)$response->getBody();

        $data = Json::decode($body);
        self::assertEquals([
            'errors' => [
                'productId' => 'This is not a valid UUID.'
            ]
        ], $data);
    }
}