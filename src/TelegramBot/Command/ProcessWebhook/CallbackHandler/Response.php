<?php

namespace App\TelegramBot\Command\ProcessWebhook\CallbackHandler;

class Response
{
    public function __construct(
        public bool $status,
        public string $message,
        public ?array $replyMarkup = null,
    ){}
}