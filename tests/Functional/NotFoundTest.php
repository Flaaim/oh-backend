<?php

namespace Test\Functional;



use DMS\PHPUnitExtensions\ArraySubset\ArraySubsetAsserts;

class NotFoundTest extends WebTestCase
{
    use ArraySubsetAsserts;
    public function testSuccess(): void
    {
        $response = $this->handle(self::json('GET', '/'));

        self::assertEquals(404, $response->getStatusCode());
        self::assertJson($body = (string)$response->getBody());

        $data = Json::decode($body);

        self::assertArraySubset([
            'message' => 'Not found',
        ], $data);
    }
}