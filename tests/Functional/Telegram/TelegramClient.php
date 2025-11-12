<?php

namespace Test\Functional\Telegram;

use Telegram\Bot\Api;

class TelegramClient
{
    public function __construct(private readonly Api $telegram)
    {}

    public function deleteWebhook(): bool
    {
        return $this->telegram->deleteWebhook();
    }
}