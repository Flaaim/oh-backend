<?php

namespace App\TelegramBot\Command;

class Response
{
    public function __construct(
        public string $status,
        public string $message,
    )
    {}
}