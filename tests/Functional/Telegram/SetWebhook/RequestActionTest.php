<?php

namespace Test\Functional\Telegram\SetWebhook;

use Test\Functional\Json;
use Test\Functional\WebTestCase;

class RequestActionTest extends WebTestCase
{
    public function testSetWebhook(): void
    {
        $response = $this->app()->handle(self::json('POST', '/payment-service/telegram/set-webhook', [
            'url' => 'https://olimpoks-help.ru/payment-service/telegram/test-set-webhook',
        ]));

        self::assertEquals(200, $response->getStatusCode());
        self::assertJson($body = (string) $response->getBody());

        $data = Json::decode($body);

        self::assertEquals([
            'status' => 'success',
            'message' => 'Webhook was successfully set to: https://olimpoks-help.ru/payment-service/telegram/test-set-webhook',
            'data' => []
        ], $data);
    }
    public function testEmpty(): void
    {
        $response = $this->app()->handle(self::json('POST', '/payment-service/telegram/set-webhook'));
        self::assertEquals(422, $response->getStatusCode());

        self::assertJson($body = (string) $response->getBody());

        $data = Json::decode($body);

        self::assertEquals(['errors' => [
            'url' => 'This value should not be blank.'
        ]], $data);
    }

    public function testSetWebhookFailed(): void
    {
        $response = $this->app()->handle(self::json('POST', '/payment-service/telegram/set-webhook', [
            'url' => 'https://test'
        ]));
        self::assertEquals(200, $response->getStatusCode());

        self::assertJson($body = (string) $response->getBody());
    }
    public function testInvalid(): void
    {
        $response = $this->app()->handle(self::json('POST', '/payment-service/telegram/set-webhook', [
            'url' => 'invalid_url'
        ]));
        self::assertEquals(422, $response->getStatusCode());
        self::assertJson($body = (string) $response->getBody());

        $data = Json::decode($body);

        self::assertEquals([
            'errors' => [
                'url' => 'This value is not a valid URL.'
            ]
        ], $data);
    }

    public function tearDown(): void
    {
        $this->telegram()->deleteWebhook();
    }
}