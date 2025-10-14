<?php

use App\Flusher;
use App\Payment\Command\HookPayment\Handler as HookPaymentHandler;
use App\Payment\Entity\PaymentRepository;
use App\Payment\Service\ProductSender;
use App\Product\Entity\ProductRepository;
use App\Shared\Domain\Service\Payment\Provider\YookassaProvider;
use App\Shared\Domain\Service\Payment\WebhookParser\YookassaWebhookParser;
use App\Shared\Domain\Service\Template\TemplatePath;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Twig\Environment;

return [
    HookPaymentHandler::class => function(ContainerInterface $c){
        $yookassaWebhookParser = new YookassaWebhookParser();

        $yookassaProvider = $c->get(YookassaProvider::class);

        $productSender = new ProductSender(
            $c->get(MailerInterface::class),
            $c->get(TemplatePath::class),
            $c->get(Environment::class),
            $logger = $c->get(LoggerInterface::class),
        );

        $em = $c->get(EntityManagerInterface::class);

        return new HookPaymentHandler(
            $yookassaWebhookParser,
            $yookassaProvider,
            $productSender,
            new ProductRepository($em),
            new PaymentRepository($em),
            new Flusher($em),
            $logger
        );
    },
];