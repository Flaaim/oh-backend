<?php

namespace App\TelegramBot\Command\ProcessWebhook\MessageHandler;

use App\TelegramBot\Command\Response;
use Telegram\Bot\Api;
use Telegram\Bot\Exceptions\TelegramSDKException;
use Telegram\Bot\Objects\Update;

class Handler
{
    public function __construct(
        private readonly Api $telegram,
        private readonly Command $command,
    )
    {}
    public function handle(Update $update): Response
    {
        $message = $update->getMessage();
        $responseText = $this->processCommand($message->get('text'));
        try{
            $this->telegram->sendMessage([
                'chat_id' => $message->getChat()->getId(),
                'parse_mode' => 'HTML',
                'text' => $responseText,
            ]);

            return new Response(
                'success',
                'Message processed successfully',
                [
                    'chat_id' => $message->getChat()->getId(),
                    'text' => $responseText,
                ]
            );
        }catch (TelegramSDKException $e){
            return new Response('error', "Failed to sendMessage: " . $e->getMessage());
        }
    }

    private function processCommand(string $text): string
    {
        return $this->command->processCommand($text);
    }
}