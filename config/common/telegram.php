<?php

declare(strict_types=1);

use App\TelegramBot\Service\ChannelChecker;
use GuzzleHttp\Client;
use Psr\Container\ContainerInterface;
use Telegram\Bot\Api;

return [
    Api::class => function (ContainerInterface $container) {
        $config = $container->get('config');

        return new Api($config['telegram']['token']);
    },
    'config' => [
        'telegram' => [
            'token' => getenv('TELEGRAM_BOT_TOKEN'),
            'channel_for_subscribe' => (int)getenv('TELEGRAM_CHANNEL_FOR_SUBSCRIBE'),
        ]
    ],
    ChannelChecker::class => function (ContainerInterface $container) {
        $config = $container->get('config');
        return new ChannelChecker(
            $config['telegram']['token'],
            $config['telegram']['channel_for_subscribe'],
            new Client([
                'base_uri' => 'https://api.telegram.org/',
            ])
        );
    }
];