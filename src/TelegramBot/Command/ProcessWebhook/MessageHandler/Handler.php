<?php

namespace App\TelegramBot\Command\ProcessWebhook\MessageHandler;

use App\TelegramBot\Command\WebhookResponse;
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
    public function handle(Update $update): WebhookResponse
    {
        $message = $update->getMessage();

        if (!$message->has('text') || empty($message->get('text'))) {
            return new WebhookResponse(
                'success',
                'Message without text ignored',
                ['chat_id' => $message->getChat()->getId(), 'has_text' => false]
            );
        }

        $response = $this->processCommand($message->get('text'));
        try{
            $messageParams  = [
              'chat_id' => $message->getChat()->getId(),
              'parse_mode' => 'HTML',
              'text' => $response->message,
            ];
            if($response->replyMarkup !== null){
                $messageParams['reply_markup'] = json_encode($response->replyMarkup);
            }
            $this->telegram->sendMessage($messageParams);

            return new WebhookResponse(
                'success',
                'Message processed successfully',
                [
                    'response' => $messageParams,
                ]
            );
        }catch (TelegramSDKException $e){
            return new WebhookResponse('error', "Failed to sendMessage: " . $e->getMessage());
        }
    }

    private function processCommand(string $text): Response
    {
        return $this->command->processCommand($text);
    }
}