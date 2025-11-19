<?php

namespace App\TelegramBot\Test\Command\MessageHandler;

use App\TelegramBot\Command\ProcessWebhook\MessageHandler\Command;
use App\TelegramBot\Command\ProcessWebhook\MessageHandler\Response;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class CommandTest extends TestCase
{

    public function testCase(): void
    {
        $value = strtoupper('/start');
        $command = new Command();
        self::assertEquals(
            new Response(
                "Добро пожаловать в бота сайта https://olimpoks-help.ru.\n\n"
                . "Доступные команды:\n"
                . "/help - Получить помощь",
                [
                    'inline_keyboard' => [
                        [
                            ['text' => '🚀 Получить ответы А.1', 'callback_data' => 'get_answers'],
                        ],
                    ]
                ]
            ),
            $command->processCommand($value));
    }
    #[dataProvider('dataProvider')]
    public function testSuccess($message, $expected): void
    {
        $command = new Command();
        self::assertEquals($expected, $command->processCommand($message));
    }


    public static function dataProvider(): array
    {
        return [
            [
                '/start', new Response(
                    "Добро пожаловать в бота сайта https://olimpoks-help.ru.\n\n"
                    . "Доступные команды:\n"
                    . "/help - Получить помощь",
                    [
                        'inline_keyboard' => [
                            [
                                ['text' => '🚀 Получить ответы А.1', 'callback_data' => 'get_answers'],
                            ],
                        ]
                    ]
                ),
            ],
            [
                '/help', new Response(
                    "Помощь по боту:\n\n"
                    . "/start - Начать работу с ботом\n"
                    . "/help - Получить эту справку\n\n"
                    . "Сайт: https://olimpoks-help.ru"
                ),
            ],
            [
                'message', new Response("Вы сказали: \"message\"\n\nИспользуйте /help для списка команд", null)
            ],
            [
                '/test', new Response("Неизвестная команда: \"/test\"\n\nИспользуйте /help для списка доступных команд", null)
            ],
            [
                '/START', new Response(
                    "Добро пожаловать в бота сайта https://olimpoks-help.ru.\n\n"
                    . "Доступные команды:\n"
                    . "/help - Получить помощь",
                    [
                        'inline_keyboard' => [
                            [
                                ['text' => '🚀 Получить ответы А.1', 'callback_data' => 'get_answers'],
                            ],
                        ]
                    ],
                )
            ],
        ];
    }

}