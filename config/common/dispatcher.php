<?php

declare(strict_types=1);

use App\Shared\Domain\Event\Payment\PaymentSubscriber;
use Psr\Container\ContainerInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

return [
    EventDispatcher::class => function (ContainerInterface $container) {
        $dispatcher = new EventDispatcher();

        $subscribers = $container->get('config')['event_subscribers'];
        foreach ($subscribers as $className) {
            $subscriber = $container->get($className);
            if ($subscriber instanceof EventSubscriberInterface) {
                $dispatcher->addSubscriber($subscriber);
            }
        }

        return $dispatcher;
    },
    'config' => [
        'event_subscribers' => [
            PaymentSubscriber::class,
        ],
    ],
];
