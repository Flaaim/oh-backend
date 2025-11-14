<?php

namespace App\TelegramBot\Test\Command\MessageHandler;



use App\TelegramBot\Command\ProcessWebhook\MessageHandler\Command;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class CommandTest extends TestCase
{
    #[dataProvider('dataProvider')]
    public function testSuccess($message, $expected): void
    {
        $command = new Command();
        self::assertEquals($command->processCommand($message), $expected);
    }


    public static function dataProvider(): array
    {
        return [
            [
                '/start', "Добро пожаловать в бота сайта https://olimpoks-help.ru.\n\n"
                    . "Доступные команды:\n"
                    . "/help - Получить помощь",
            ],
            [
                '/help', "Помощь по боту:\n\n"
                    . "/start - Начать работу с ботом\n"
                    . "/help - Получить эту справку\n\n"
                    . "Сайт: https://olimpoks-help.ru"
            ],
            [
                'message', "Вы сказали: \"message\"\n\nИспользуйте /help для списка команд"
            ],
            [
                '/test', "Неизвестная команда: /test\n\nИспользуйте /help для списка доступных команд"
            ],
            [
                '/START', "Добро пожаловать в бота сайта https://olimpoks-help.ru.\n\n"
                . "Доступные команды:\n"
                . "/help - Получить помощь"
            ],
        ];
    }

}