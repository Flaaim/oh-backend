<?php

namespace Test\Functional\Telegram\ProcessWebhook;

use Test\Functional\Json;
use Test\Functional\WebTestCase;

class RequestActionTest extends WebTestCase
{
    public function testSuccess(): void
    {
        $response = $this->app()->handle(self::json('POST', '/payment-service/telegram/webhook',
            $this->updateData()));

        self::assertEquals(200, $response->getStatusCode());

        self::assertJson($body = (string)$response->getBody());
        $data = Json::decode($body);

        self::assertEquals([
            'status' => 'success',
            'message' => 'Message processed successfully',
            'data' => [
                'chat_id' => $this->updateData()['message']['chat']['id'],
                'text' => $this->updateData()['message']['text'],
            ]
        ], $data);
    }
    public function testEmpty(): void
    {
        $response = $this->app()->handle(self::json('POST', '/payment-service/telegram/webhook'));

        self::assertEquals(422, $response->getStatusCode());

        self::assertJson($body = (string)$response->getBody());
        $data = Json::decode($body);
        self::assertEquals([
            'errors' => [
                'updateData' => 'This value should not be blank.',
                'updateData[update_id]' => 'This field is missing.'
            ]
        ], $data);
    }
    public function testInvalid(): void
    {

        $response = $this->app()->handle(self::json(
            'POST',
            '/payment-service/telegram/webhook',
            ['test' => 'invalid', 'chat' => 123343]
        ));

        self::assertEquals(422, $response->getStatusCode());

        self::assertJson($body = (string)$response->getBody());
        $data = Json::decode($body);
        self::assertEquals([
            'errors' => [
                'updateData[update_id]' => 'This field is missing.'
            ]
        ], $data);
    }
    private function updateData(): array
    {
        return [
            'update_id' => 123456,
            'message' => [
                'message_id' => 1,
                'chat' => [
                    'id' => 1954013093,
                    'type' => 'private',
                    'username' => 'Flaaim'
                ],
                'text' => '/start'
            ]
        ];
    }
}