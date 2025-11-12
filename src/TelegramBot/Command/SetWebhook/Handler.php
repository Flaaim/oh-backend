<?php

namespace App\TelegramBot\Command\SetWebhook;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use Telegram\Bot\Api;
use Telegram\Bot\Exceptions\TelegramSDKException;


class Handler
{
    public function __construct(
        private readonly Api $telegram,
    )
    {}
    public function handle(Command $command): Response
    {
        try{
            $response = $this->telegram->setWebhook([
                'url' => $command->url
            ]);
            return new Response(
                $response ? 'success' : 'error',
                "Webhook was successfully set to: {$command->url}"
            );
        }catch (TelegramSDKException $e){
            return new Response('error', "Failed to set webhook: " . $e->getMessage());
        }
    }
}