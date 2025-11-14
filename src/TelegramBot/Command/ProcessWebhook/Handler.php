<?php

namespace App\TelegramBot\Command\ProcessWebhook;

use App\TelegramBot\Command\Response;
use Telegram\Bot\Api;
use Telegram\Bot\Exceptions\TelegramSDKException;
use Telegram\Bot\Objects\Message;
use Telegram\Bot\Objects\Update;

class Handler
{
    public function __construct(
        private readonly Api $telegram
    )
    {}
    public function handle(Command $command): Response
    {
        $update = new Update($command->updateData);
        $message = $update->getMessage();

        if($update->isType('message')){
            try{
                $this->telegram->sendMessage([
                    'chat_id' => $message->getChat()->getId(),
                    'parse_mode' => 'HTML',
                    'text' => $message->get('text'),
                ]);

                return new Response(
                    'success',
                    'Message processed successfully',
                    [
                    'chat_id' => $message->getChat()->getId(),
                    'text' => $message->get('text'),
                    ]
                );
            }catch (TelegramSDKException $e){
                return new Response('error', "Failed to sendMessage: " . $e->getMessage());
            }
        }
    }
}