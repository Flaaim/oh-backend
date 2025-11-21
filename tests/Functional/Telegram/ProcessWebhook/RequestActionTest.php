<?php

namespace Test\Functional\Telegram\ProcessWebhook;

use Test\Functional\Json;
use Test\Functional\WebTestCase;

class RequestActionTest extends WebTestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->loadFixtures([
            RequestFixture::class,
        ]);
    }
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
                    . "Чтобы получить ответы по А.1 необходимо подписаться на канал https://t.me/olimpoks_help\n\n",
                'parse_mode' => 'HTML',
                'affected_rows' => 1,
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
    public function testCallbackQuerySuccess(): void
    {
        $response = $this->app()->handle(self::json(
            'POST',
            '/payment-service/telegram/webhook',
            $this->updateDataWithCallbackQuery())
        );
        self::assertEquals(200, $response->getStatusCode());
        self::assertJson($body = (string)$response->getBody());

        $data = Json::decode($body);

        self::assertEquals([
            'status' => 'success',
            'message' => 'Message processed successfully',
            'data' => [
                'chat_id' => $this->updateDataWithCallbackQuery()['callback_query']['from']['id'],
                'parse_mode' => 'HTML',
                'text' => 'Подписка подтверждена! Отправляю файл.',
            ]
        ], $data);
    }

    public function testCallbackQueryFailed(): void
    {
        $chat_id = 12345698;
        $data = $this->updateDataWithCallbackQuery();
        $data['callback_query']['from']['id'] = $chat_id;

        $response = $this->app()->handle(self::json(
            'POST',
            '/payment-service/telegram/webhook',
            $data)
        );
        self::assertEquals(200, $response->getStatusCode());
        self::assertJson($body = (string)$response->getBody());
        $data = Json::decode($body);
        self::assertEquals([
            'status' => 'success',
            'message' => 'Message processed successfully',
            'data' => [
                'chat_id' => $this->updateDataWithCallbackQuery()['callback_query']['from']['id'],
                'parse_mode' => 'HTML',
                'text' => 'Вы не подписались на канал! Чтобы получить ответы по А.1 необходимо подписаться на канала https://t.me/olimpoks_help',
                'reply_markup' =>
                    json_encode(['inline_keyboard' => [
                    [
                        ['text' => '🚀 Получить ответы А.1', 'callback_data' => 'get_answers']
                    ]
                ]])
            ]
        ], $data);
    }
    private function updateData(): array
    {
        return [
            'update_id' => 123456,
            'message' => [
                'message_id' => 123,
                'chat' => [
                    'id' => 1954013093,
                    'type' => 'private',
                    'username' => 'Flaaim'
                ],
                'text' => '/start'
            ]
        ];
    }
    private function updateDataWithCallbackQuery(): array
    {
        return [
            'update_id' => 123456,
            'callback_query' => [
                'id' => 'test_callback_' . uniqid(),
                'from' => [
                    'id' => 1954013093,
                    'is_bot' => false,
                    'first_name' => 'Flaaim',
                    'username' => 'Flaaim'
                ],
                'message' => [
                    'message_id' => 123,
                    'from' => [
                        'id' => 123456789,
                        'is_bot' => true,
                        'first_name' => 'Test Bot'
                    ],
                    'chat' => [
                        'id' => 1954013093,
                        'type' => 'private',
                        'username' => 'Flaaim',
                        'first_name' => 'Flaaim'
                    ],
                    'date' => time(),
                    'text' => 'Нажмите кнопку для получения ответов'
                ],
                'chat_instance' => '123456789',
                'data' => 'get_answers',
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