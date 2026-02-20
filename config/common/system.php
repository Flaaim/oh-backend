<?php


declare(strict_types=1);


use App\Access\Command\OpenAccess\Handler;
use App\Payment\Service\Delivery\Access\AccessDelivery;
use App\Payment\Service\Delivery\Access\AccessSender;
use App\Payment\Service\Delivery\DeliveryFactory;
use App\Payment\Service\Delivery\Product\FileDelivery;
use App\Payment\Service\Delivery\Product\FileSender;
use App\Product\Entity\ProductRepository;
use App\Shared\Domain\Service\Template\RootPath;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Twig\Environment;

return [
    'config' => [
        'debug' => (bool)getenv('APP_DEBUG'),
        'login' => getenv('AUTH_LOGIN'),
        'password' => getenv('AUTH_PASSWORD'),
        'template_paths' => __DIR__ . '/../../public/templates',
    ],
    RootPath::class => function (ContainerInterface $container) {
        return new RootPath(
            $container->get('config')['template_paths'],
        );
    },
    DeliveryFactory::class => function (ContainerInterface $container) {
        $em = $container->get(EntityManagerInterface::class);
        $productRepository = new ProductRepository($em);

        $fileSender = new FileSender(
            $container->get(MailerInterface::class),
            $container->get(Environment::class),
            $container->get(LoggerInterface::class),
        );
        $fileDelivery = new FileDelivery($fileSender, $container->get(RootPath::class),);

        $accessSender = new AccessSender(
            $container->get(MailerInterface::class),
            $container->get(Environment::class),
            $container->get(LoggerInterface::class),
        );

        $accessDelivery = new AccessDelivery(
            $container->get(Handler::class),
            $accessSender
        );

        return new DeliveryFactory($productRepository, [$fileDelivery, $accessDelivery]);
    }

];