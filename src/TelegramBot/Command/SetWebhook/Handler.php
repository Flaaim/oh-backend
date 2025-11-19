<?php

namespace App\TelegramBot\Command\SetWebhook;

use App\TelegramBot\Command\WebhookResponse;
use Telegram\Bot\Api;
use Telegram\Bot\Exceptions\TelegramSDKException;


class Handler
{
    public function __construct(
        private readonly Api $telegram,
    )
    {}
    public function handle(Command $command): WebhookResponse
    {
        try{
            $response = $this->telegram->setWebhook([
                'url' => $command->url
            ]);
            return new WebhookResponse(
                $response ? 'success' : 'error',
                "Webhook was successfully set to: {$command->url}"
            );
        }catch (TelegramSDKException $e){
            return new WebhookResponse('error', "Failed to set webhook: " . $e->getMessage());
        }
    }
}