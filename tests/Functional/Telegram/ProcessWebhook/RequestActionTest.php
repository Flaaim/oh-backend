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
                'text' => "Добро пожаловать в бота сайта https://olimpoks-help.ru.\n\n"
                    . "Доступные команды:\n"
                    . "/help - Получить помощь",
                'parse_mode' => 'HTML',
                'reply_markup' =>
                    json_encode(['inline_keyboard' => [
                        [
                            ['text' => '🚀 Получить ответы А.1', 'callback_data' => 'get_answers']
                        ]
                    ]])

            ]
        ], $data);
    }
    public function testEmptyText(): void
    {
        $updateData = $this->updateData();
        $updateData['message']['text'] = '';
        $response = $this->app()->handle(self::json(
            'POST', '/payment-service/telegram/webhook', $updateData)
        );
        self::assertEquals(200, $response->getStatusCode());

        self::assertJson($body = (string)$response->getBody());
        $data = Json::decode($body);


        self::assertEquals([
            'status' => 'success',
            'message' => 'Message without text ignored',
            'data' => [
                'chat_id' => $this->updateData()['message']['chat']['id'], 'has_text' => false
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
    public function testUnsupported(): void
    {
        $response = $this->app()->handle(self::json('POST', '/payment-service/telegram/webhook', $this->updatedPoll()));

        self::assertEquals(200, $response->getStatusCode());

        self::assertJson($body = (string)$response->getBody());
        $data = Json::decode($body);

        self::assertEquals([
            'status' => 'success',
            'message' => 'Unsupported update type ignored',
            'data' => [
                'updated_type' => 'unknown'
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

    private function updatedPoll(): array
    {
        return [
            'update_id' => 123456,
            'poll' => [
                'id' => 1954013093,
                'question' => 'some question',
                'options' => [],
                'total_voter_count' => 1,
                'is_closed' => false,
                'is_anonymous' => false,
                'type' => 'regular',
                'allows_multiple_answers' => false,
            ]
        ];
    }
}