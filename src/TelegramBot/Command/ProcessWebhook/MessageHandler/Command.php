<?php

namespace App\TelegramBot\Command\ProcessWebhook\MessageHandler;

class Command
{
    public function processCommand(string $command): Response
    {
        $normalizedCommand = strtolower(trim($command));

        return match ($normalizedCommand) {
            '/start' => $this->getStartMessage(),
            '/help' => $this->getHelpMessage(),
            default => $this->getDefaultMessage($command),
        };
    }

    private function getStartMessage(): Response
    {
        $text =  "Добро пожаловать в бота сайта https://olimpoks-help.ru.\n\n"
            . "Чтобы получить ответы по А.1 необходимо подписаться на канал https://t.me/olimpoks_help\n\n";
        $replyMarkup = [
            'inline_keyboard' => [
                [
                    ['text' => '🚀 Получить ответы А.1', 'callback_data' => 'get_answers'],
                ],
            ]
        ];

        return new Response(
            $text,
            $replyMarkup
        );
    }

    private function getHelpMessage(): Response
    {
        $text =  "Помощь по боту:\n\n"
            . "/start - Начать работу с ботом\n"
            . "/help - Получить эту справку\n\n"
            . "Сайт: https://olimpoks-help.ru";

        return new Response($text);
    }

    private function getDefaultMessage(string $text): Response
    {
        if (!str_starts_with($text, '/')) {
            $text = "Вы сказали: \"{$text}\"\n\nИспользуйте /help для списка команд";
            return new Response($text);
        }

        $text =  "Неизвестная команда: \"{$text}\"\n\nИспользуйте /help для списка доступных команд";
        return new Response($text);
    }
}