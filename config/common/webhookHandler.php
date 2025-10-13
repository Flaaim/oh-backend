<?php

use App\Flusher;
use App\Payment\Command\HookPayment\Handler as HookPaymentHandler;
use App\Payment\Entity\PaymentRepository;
use App\Payment\Service\ProductSender;
use App\Product\Entity\ProductRepository;
use App\Shared\Domain\Service\Payment\Provider\YookassaProvider;
use App\Shared\Domain\Service\Payment\WebhookParser\YookassaWebhookParser;
use App\Shared\Domain\TemplatePath;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use Symfony\Component\Mailer\MailerInterface;

return [
    HookPaymentHandler::class => function(ContainerInterface $c){
        return new HookPaymentHandler(
            new YookassaWebhookParser(),
            $c->get(YookassaProvider::class),
            new ProductSender($c->get(MailerInterface::class), $c->get(TemplatePath::class)),
            new ProductRepository($c->get(EntityManagerInterface::class)),
            new PaymentRepository($c->get(EntityManagerInterface::class)),
            new Flusher($c->get(EntityManagerInterface::class))
        );
    },
];