<?php

namespace Test\Functional\Result;

use DMS\PHPUnitExtensions\ArraySubset\ArraySubsetAsserts;
use Test\Functional\Json;
use Test\Functional\WebTestCase;

class RequestActionTest extends WebTestCase
{
    use ArraySubsetAsserts;

    protected function setUp(): void
    {
        parent::setUp();
        $this->loadFixtures([
            RequestFixture::class,
        ]);
    }
    public function testSuccess(): void
    {
        $returnToken = '392b1c38-f3e4-4533-a6cb-5b4e7c08d91f';

        $response = $this->handle(self::json('POST', '/payment-service/result', [
            'returnToken' => $returnToken
        ]));

        $this->assertEquals(200, $response->getStatusCode());
        self::assertJson($body = (string)$response->getBody());

        $data = Json::decode($body);

        self::assertArraySubset([
            'returnToken' => $returnToken,
            'status' => 'succeeded',
            'email' => 'test@app.ru'
        ], $data);
    }
}