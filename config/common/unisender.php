<?php

declare(strict_types=1);

use App\Shared\Domain\Service\Distribution\DistributionInterface;
use App\Shared\Infrastructure\Distribution\UniSender;
use GuzzleHttp\Client;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;


return [
    DistributionInterface::class => function (ContainerInterface $container) {
        $client = new Client([
            'base_uri' => ' https://goapi.unisender.ru/ru/transactional/api/v1/'
        ]);

        $logger = $container->get(LoggerInterface::class);
        $apiKey = $container->get('config')['uniSender']['apiKey'];

        return new UniSender($client, $logger, $apiKey);
    },
    'config' => [
        'uniSender' => [
            'apiKey' => getenv('UNI_SENDER_API'),
        ]
    ]
];