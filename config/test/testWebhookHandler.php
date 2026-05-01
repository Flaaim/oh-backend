<?php

declare(strict_types=1);

use App\Flusher;
use App\Payment\Command\HookPayment\Handler as HookPaymentHandler;
use App\Payment\Command\HookPayment\SendProduct\Handler as SendProductHandler;
use App\Payment\Entity\PaymentRepository;
use App\Payment\Service\Delivery\DeliveryFactory;
use App\Shared\Domain\Service\Payment\WebhookParser\YookassaWebhookParser;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Test\Functional\Payment\TestPaymentProvider;

return [
    HookPaymentHandler::class => function (ContainerInterface $c) {

        $yookassaWebhookParser = new YookassaWebhookParser();

        $yookassaProvider = $c->get(TestPaymentProvider::class);

        $delivery = $c->get(DeliveryFactory::class);
        $em = $c->get(EntityManagerInterface::class);

        $sendProductHandler = new SendProductHandler(
            $delivery,
            $c->get(EventDispatcher::class)
        );

        $logger = $c->get(LoggerInterface::class);

        return new HookPaymentHandler(
            $yookassaWebhookParser,
            $yookassaProvider,
            new PaymentRepository($em),
            new Flusher($em),
            $sendProductHandler,
            $logger,
        );
    },
];
