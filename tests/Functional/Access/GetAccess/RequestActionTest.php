<?php

declare(strict_types=1);

namespace Test\Functional\Access\GetAccess;

use App\Access\Service\UuidConverter;
use App\Product\Test\TempDir;
use DMS\PHPUnitExtensions\ArraySubset\ArraySubsetAsserts;
use Test\Functional\Json;
use Test\Functional\WebTestCase;

/**
 * @internal
 */
class RequestActionTest extends WebTestCase
{
    use ArraySubsetAsserts;
    protected function setUp(): void
    {
        parent::setUp();
        $this->loadFixtures([RequestFixture::class]);
        $this->tempDir = TempDir::create();
    }
    public function testSuccess(): void
    {
        $encodedToken = $this->getEncodedString('b035e3dc-cadc-45dd-85a1-817b6060d6fe');
        $response = $this->app()->handle(self::access('GET', '/payment-service/access/get?token='.$encodedToken));

        self::assertEquals(200, $response->getStatusCode());

        self::assertJson($body = (string)$response->getBody());
        $data = Json::decode($body);

        self::assertEquals([
            'name' => 'Оказание первой помощи пострадавшим',
            'cipher' => 'ОТ 201.18',
            'expiredAt' => (new \DateTimeImmutable('+ 3 days'))->format('Y-m-d'),
            'email' => 'test@email.ru',
            'productId' => $this->getEncodedString('b38e76c0-ac23-4c48-85fd-975f32c8801f'),
        ], $data);

    }

    public function testSessionSuccessSameDevice(): void
    {
        $encodedToken = $this->getEncodedString('b035e3dc-cadc-45dd-85a1-817b6060d6fe');
        $sessionId = bin2hex(random_bytes(32));

        $response = $this->app()->handle(self::access('GET', '/payment-service/access/get?token='.$encodedToken, [], $sessionId));

        self::assertEquals(200, $response->getStatusCode());
        $response = $this->app()->handle(self::access('GET', '/payment-service/access/get?token='.$encodedToken, [], $sessionId));

        self::assertEquals(200, $response->getStatusCode());
    }
    public function testFailedWithDifferentDevice(): void
    {
        $encodedToken = $this->getEncodedString('b035e3dc-cadc-45dd-85a1-817b6060d6fe');
        $sessionId = bin2hex(random_bytes(32));

        $response = $this->app()->handle(self::access('GET', '/payment-service/access/get?token='.$encodedToken, [], $sessionId));
        self::assertEquals(200, $response->getStatusCode());

        $response = $this->app()->handle(self::access('GET', '/payment-service/access/get?token='.$encodedToken));

        self::assertEquals(200, $response->getStatusCode());
    }
    public function testNotFound(): void
    {
        $encodedToken = $this->getEncodedString('94710e2e-02e5-439c-8674-d75178c3b59a');
        $response = $this->app()->handle(self::access('GET', '/payment-service/access/get?token='.$encodedToken));

        self::assertEquals(400, $response->getStatusCode());
        self::assertJson($body = (string)$response->getBody());
        $data = Json::decode($body);

        self::assertArraySubset([
            'message' => 'Access not found. token: 94710e2e-02e5-439c-8674-d75178c3b59a',
        ], $data);
    }
    public function invalidToken(): void
    {
        $response = $this->app()->handle(self::access('GET', '/payment-service/access/get?token=invalid'));

        self::assertEquals(422, $response->getStatusCode());
        self::assertJson($body = (string)$response->getBody());

        $data = Json::decode($body);

        self::assertEquals([
            'errors' => [
                'encodedToken' => 'The format URL must be a valid.',
            ],
        ], $data);
    }

    public function testEmpty(): void
    {
        $response = $this->app()->handle(self::access('GET', '/payment-service/access/get?token='));

        self::assertEquals(422, $response->getStatusCode());
        self::assertJson($body = (string)$response->getBody());

        $data = Json::decode($body);

        self::assertEquals([
            'errors' => [
                'token' => 'This value should have exactly 22 characters.',
            ],
        ], $data);
    }
    public function testExpired(): void
    {
        $encodedToken = $this->getEncodedString('02065614-eb7b-49a9-852d-0490972d4891');
        $response = $this->app()->handle(self::access('GET', '/payment-service/access/get?token='.$encodedToken));

        self::assertEquals(410, $response->getStatusCode());

        self::assertJson($body = (string)$response->getBody());

        $data = Json::decode($body);

        self::assertArraySubset([
            'message' => 'Срок действия доступа к файлу истек...',
            'productId' => 'b38e76c0-ac23-4c48-85fd-975f32c8801f',
        ], $data);
    }
    protected function tearDown(): void
    {
        $this->tempDir->clear();
    }

    private function getEncodedString(string $uuid): string
    {
        return (new UuidConverter())->encode($uuid);
    }

}
