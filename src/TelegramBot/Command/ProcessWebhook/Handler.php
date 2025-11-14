<?php

namespace App\TelegramBot\Command\ProcessWebhook;

use App\TelegramBot\Command\Response;
use App\TelegramBot\Command\ProcessWebhook\MessageHandler\Handler as MessageHandler;
use Telegram\Bot\Objects\Update;

class Handler
{
    public function __construct(private readonly MessageHandler $messageHandler){}
    public function handle(Command $command): Response
    {
        $update = new Update($command->updateData);

        return match ($this->getUpdateType($update)) {
            'message' => $this->messageHandler->handle($update),
            default => new Response(
                'success',
                'Unsupported update type ignored',
                ['updated_type' => $this->getUpdateType($update)]
            ),
        };
    }
    private function getUpdateType(Update $update): string
    {
        if($update->isType('message')){
            return 'message';
        }
        if($update->isType('callback_query')){
            return 'callback_query';
        }
        if($update->isType('inline_query')){
            return 'inline_query';
        }
        return 'unknown';
    }
}
