<?php

declare(strict_types=1);

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
        ]
    ]
];