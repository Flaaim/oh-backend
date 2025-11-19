<?php

namespace App\TelegramBot\Command\ProcessWebhook\MessageHandler;

class Response
{
    public function __construct(
        public readonly string $message,
        public readonly ?array $replyMarkup = null
    )
    {}


}