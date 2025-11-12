<?php

namespace App\TelegramBot\Command\SetWebhook;

class Response
{
    public function __construct(
        public string $status,
        public string $message,
    )
    {}
}