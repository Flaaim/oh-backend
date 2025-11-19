<?php

namespace App\TelegramBot\Command;

class WebhookResponse
{
    public function __construct(
        public string $status,
        public string $message,
        public array $data = []
    )
    {}
}