<?php

namespace Test\Functional\Payment\Result;

use App\Payment\Entity\Token;
use DMS\PHPUnitExtensions\ArraySubset\ArraySubsetAsserts;
use Test\Functional\Json;
use Test\Functional\Payment\PaymentBuilder;
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
        $returnToken = $this->getReturnToken()->getValue();
        $response = $this->app()->handle(self::json('POST', '/payment-service/result', [
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

    private function getReturnToken(): Token
    {
        $payment = (new PaymentBuilder())->build();

        return $payment->getReturnToken();
    }
}