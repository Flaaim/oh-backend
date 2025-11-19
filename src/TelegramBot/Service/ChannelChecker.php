<?php

namespace App\TelegramBot\Service;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Webmozart\Assert\Assert;

class ChannelChecker
{
    public function __construct(
        private readonly string $token,
        private readonly int $channelId,
        private readonly Client $client,
    )
    {
        Assert::notEmpty($this->token);
    }

    public function checkChannel(int $chatId): bool
    {
        try{
            $response = $this->client->get('/bot'. $this->token .'/getChatMember?', [
                'query' => [
                    'chat_id' => $this->channelId,
                    'user_id' => $chatId
                ]
            ]);
        }catch (GuzzleException $e){
            return false;
        }

        $result = json_decode($response->getBody()->getContents(), true);

        if($result['ok'] === true){
            $status = $result['result']['status'];
            return in_array($status, ['administrator', 'member', 'creator']);
        }
        return false;

    }
}