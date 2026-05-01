<?php

declare(strict_types=1);

use App\Ticket\Entity\Ticket;
use App\Ticket\Entity\TicketRepository;
use App\Ticket\Service\ImageDownloader\PathManager;
use App\Ticket\Service\ImageDownloader\UrlBuilder;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Psr\Container\ContainerInterface;

return [
    TicketRepository::class => function (ContainerInterface $container): TicketRepository {
        /** @var EntityManagerInterface $em */
        $em = $container->get(EntityManagerInterface::class);
        /** @var EntityRepository $repo */
        $repo = $em->getRepository(Ticket::class);
        return new TicketRepository($em, $repo);
    },
    PathManager::class => function (ContainerInterface $container) {
        return new PathManager($container->get('config')['basePath']);
    },
    UrlBuilder::class => function (ContainerInterface $container) {
        return new UrlBuilder($container->get('config')['urlPath']);
    }

];
