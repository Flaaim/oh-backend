<?php

namespace Test\Functional\Home;

use Test\Functional\Json;
use Test\Functional\WebTestCase;

class HomeTest extends WebTestCase
{
    public function testSuccess(): void
    {
        $response = $this->app()->handle(self::json('GET', '/'));

        self::assertEquals(200, $response->getStatusCode());

        self::assertJson($body = (string)$response->getBody());
        $data = Json::decode($body);

        self::assertEquals(['Hello World!'], $data);
    }

    public function testFeatures(): void
    {
        self::markTestIncomplete('Wait new feature');
        $response = $this->app()->handle(self::json('GET', '/'))->withHeader('X-Features', 'New feature');
    }
}
