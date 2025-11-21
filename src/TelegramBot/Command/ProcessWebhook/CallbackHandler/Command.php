<?php

namespace App\TelegramBot\Command\ProcessWebhook\CallbackHandler;

use App\TelegramBot\Service\ChannelChecker;
use Telegram\Bot\Objects\CallbackQuery;

class Command
{
    public function __construct(private readonly ChannelChecker $checker)
    {}
    public function processCommand(CallbackQuery $query): Response
    {
        $data = $query->data;
        $normalizedCommand = strtolower(trim($data));

        return match ($normalizedCommand){
            'get_answers' => $this->getAnswers($query->from->id),
        };
    }
    public function getAnswers(int $id): Response
    {
        $result = $this->checker->checkChannel($id);
        if($result === true){
            return new Response(
                true,
                'Подписка подтверждена! Отправляю файл.'
            );
        }

        if($result === false){
            return new Response(
                false,
                'Вы не подписались на канал! Чтобы получить ответы по А.1 необходимо подписаться на канала https://t.me/olimpoks_help',
                [
                    'inline_keyboard' => [
                        [
                            ['text' => '🚀 Получить ответы А.1', 'callback_data' => 'get_answers'],
                        ],
                    ]
                ]
            );
        }


    }


}