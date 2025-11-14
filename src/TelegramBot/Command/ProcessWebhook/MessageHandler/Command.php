<?php

namespace App\TelegramBot\Command\ProcessWebhook\MessageHandler;

class Command
{
    public function processCommand(string $command): string
    {
        $normalizedCommand = strtolower(trim($command));

        return match ($normalizedCommand) {
            '/start' => $this->getStartMessage(),
            '/help' => $this->getHelpMessage(),
            default => $this->getDefaultMessage($command),
        };
    }

    private function getStartMessage(): string
    {
        return "Добро пожаловать в бота сайта https://olimpoks-help.ru.\n\n"
            . "Доступные команды:\n"
            . "/help - Получить помощь";
    }

    private function getHelpMessage(): string
    {
        return "Помощь по боту:\n\n"
            . "/start - Начать работу с ботом\n"
            . "/help - Получить эту справку\n\n"
            . "Сайт: https://olimpoks-help.ru";
    }

    private function getDefaultMessage(string $text): string
    {
        if (!str_starts_with($text, '/')) {
            return "Вы сказали: \"{$text}\"\n\nИспользуйте /help для списка команд";
        }

        return "Неизвестная команда: {$text}\n\nИспользуйте /help для списка доступных команд";
    }
}