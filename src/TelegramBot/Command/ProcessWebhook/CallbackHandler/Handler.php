<?php

namespace App\TelegramBot\Command\ProcessWebhook\CallbackHandler;

use App\TelegramBot\Command\WebhookResponse;
use App\TelegramBot\Service\FileHandler;
use Telegram\Bot\Api;
use Telegram\Bot\Exceptions\TelegramSDKException;
use Telegram\Bot\Objects\Update;

class Handler
{
    public function __construct(
        private readonly Api $telegram,
        private readonly Command $command,
        private readonly FileHandler $fileHandler,
    )
    {}
    public function handle(Update $update): WebhookResponse
    {
        $callbackQuery = $update->callbackQuery;

        $response = $this->command->processCommand($callbackQuery);

        try{

            $messageParams = [
                'chat_id' => $callbackQuery->message->chat->id,
                'parse_mode' => 'HTML',
                'text' => $response->message,
            ];

            if ($response->replyMarkup !== null) {
                $messageParams['reply_markup'] = json_encode($response->replyMarkup);
            }

            $this->telegram->sendMessage($messageParams);

            if($response->status === true){
                $this->telegram->sendDocument([
                   'chat_id' => $callbackQuery->message->chat->id,
                    'document' => fopen($this->fileHandler->getFile(), 'r'),
                ]);
            }

            return new WebhookResponse(
                'success',
                'Message processed successfully',
                $messageParams
            );

        }catch (TelegramSDKException $e){
            return new WebhookResponse('error', "Failed to sendMessage: " . $e->getMessage());
        }
    }
}