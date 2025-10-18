<?php

namespace Test\Functional\CreatePayment;

use DMS\PHPUnitExtensions\ArraySubset\ArraySubsetAsserts;
use Test\Functional\Json;
use Test\Functional\WebTestCase;


class RequestActionTest extends WebTestCase
{
    use ArraySubsetAsserts;
    public function testSuccess(): void
    {
        $response = $this->handle(self::json('POST', '/payment-service/process-payment', [
            'email' => 'test@app.ru',
            'productId' => 'b38e76c0-ac23-4c48-85fd-975f32c8809f'
        ]));

        $this->assertEquals(201, $response->getStatusCode());
        self::assertJson($body = (string)$response->getBody());

        $data = Json::decode($body);
        self::assertArraySubset([
            'amount' => 450,
            'currency' => 'RUB',
        ],$data);
    }

    public function testNotFound(): void
    {
        $response = $this->handle(self::json('POST', '/payment-service/process-payment', [
            'email' => 'test@app.ru',
            'productId' => \Ramsey\Uuid\Uuid::uuid4()->toString(),
        ]));

        self::assertEquals(404, $response->getStatusCode());
        self::assertJson($body = (string)$response->getBody());

        $data = Json::decode($body);

        self::assertArraySubset([
            'message' => 'Product not found.',
        ], $data);

    }

    public function testInvalidEmail(): void
    {
        $response = $this->handle(self::json('POST', '/payment-service/process-payment', [
            'email' => 'invalid',
            'productId' => 'b38e76c0-ac23-4c48-85fd-975f32c8809f'
        ]));

        self::assertEquals(400, $response->getStatusCode());

        self::assertJson($body = (string)$response->getBody());

        $data = Json::decode($body);

        self::assertArraySubset([
            'message' => 'Invalid email address',
        ], $data);
    }

    public function testInvalidProductId(): void
    {
        $response = $this->handle(self::json('POST', '/payment-service/process-payment', [
            'email' => 'test@user.ru',
            'productId' => 'someInvalidProductId',
        ]));

        self::assertEquals(400, $response->getStatusCode());

        self::assertJson($body = (string)$response->getBody());

        $data = Json::decode($body);

        self::assertArraySubset([
            'message' => 'Value "someInvalidProductId" is not a valid UUID.',
        ], $data);
    }

}