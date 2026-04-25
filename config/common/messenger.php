<?php

declare(strict_types=1);

use App\Shared\Domain\Queue\Distribution\SendEmailBatchHandler;
use App\Shared\Domain\Queue\Distribution\SendEmailBatchMessage;
use App\Shared\Domain\RecipientQuery\RecipientQueryInterface;
use App\Shared\Infrastructure\Persistence\RecipientQuery;
use Psr\Container\ContainerInterface;
use Symfony\Component\Messenger\Handler\HandlerDescriptor;
use Symfony\Component\Messenger\Handler\HandlersLocator;
use Symfony\Component\Messenger\MessageBus;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Middleware\HandleMessageMiddleware;
use Symfony\Component\Messenger\Middleware\SendMessageMiddleware;
use Symfony\Component\Messenger\Transport\Sender\SendersLocator;
use App\Recipient\Command\Add\Command as AddRecipientCommand;
use App\Recipient\Command\Add\Handler as AddRecipientHandler;

return [
    MessageBusInterface::class => function (ContainerInterface $container) {
        $handlers = [
            SendEmailBatchMessage::class => [
                new HandlerDescriptor([$container->get(SendEmailBatchHandler::class), 'handle'])
            ],
            AddRecipientCommand::class => [
                new HandlerDescriptor([$container->get(AddRecipientHandler::class), 'handle'])
            ]
        ];

        $sendersLocator = new SendersLocator([
            '*' => getenv('MESSENGER_TRANSPORT_DSN') ? ['messenger.transport.async'] : []
        ], $container);

        return new MessageBus([
            new SendMessageMiddleware($sendersLocator),
            new HandleMessageMiddleware(new HandlersLocator($handlers)),
        ]);
    },
    RecipientQueryInterface::class => DI\get(RecipientQuery::class),
    RecipientQuery::class => function (Psr\Container\ContainerInterface $container) {
        return new RecipientQuery(
            $container->get(Doctrine\ORM\EntityManagerInterface::class)
        );
    },
    'config' => [
        'messenger' => [
            'async' => getenv('MESSENGER_TRANSPORT_DSN'),
        ]
    ],

];