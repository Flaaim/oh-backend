<?php

declare(strict_types=1);

namespace Test\Functional\Product\RecountPrice;

use Test\Functional\Json;
use Test\Functional\Product\Get\RequestFixture;
use Test\Functional\WebTestCase;

/**
 * @internal
 */
class RequestActionTest extends WebTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->loadFixtures([RequestFixture::class]);
    }
    public function testSuccess(): void
    {
        $response =  $this->app()->handle(self::json('POST', '/payment-service/products/recount-price', [
            'productId' => 'b38e76c0-ac23-4c48-85fd-975f32c8801f',
            'type' => 'access',
        ]));
        self::assertEquals(200, $response->getStatusCode());
        self::assertJson($body = (string)$response->getBody());

        $data = Json::decode($body);
        self::assertEquals(550.00, $data['price']);
    }
}
