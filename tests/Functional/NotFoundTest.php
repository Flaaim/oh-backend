<?php

namespace Test\Functional;



class NotFoundTest extends WebTestCase
{
    public function testSuccess(): void
    {
        $response = $this->handle(self::json('GET', '/'));

        self::assertEquals(404, $response->getStatusCode());
        self::assertJson($body = (string)$response->getBody());

        $data = Json::decode($body);

        self::assertEquals([
            'error' => 'Not found',
        ], $data);
    }
}