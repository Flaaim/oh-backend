<?php

declare(strict_types=1);

use App\TelegramBot\Service\ChannelChecker;
use App\TelegramBot\Service\FileHandler;
use GuzzleHttp\Client;
use Psr\Container\ContainerInterface;
use Telegram\Bot\Api;

return [
    Api::class => function (ContainerInterface $container) {
        $config = $container->get('config')['telegram'];

        return new Api($config['token']);
    },
    'config' => [
        'telegram' => [
            'token' => getenv('TELEGRAM_BOT_TOKEN'),
            'channel_for_subscribe' => (int)getenv('TELEGRAM_CHANNEL_FOR_SUBSCRIBE'),
            'file' => __DIR__ . '/../../public/templates/telegram/'. getenv('TG_FILE'),
        ]
    ],
    ChannelChecker::class => function (ContainerInterface $container) {
        $config = $container->get('config')['telegram'];
        return new ChannelChecker(
            $config['token'],
            $config['channel_for_subscribe'],
            new Client([
                'base_uri' => 'https://api.telegram.org/',
            ])
        );
    },
    FileHandler::class => function (ContainerInterface $container) {
        $config = $container->get('config')['telegram'];
        return new FileHandler(
            $config['file'],
        );
    }
];