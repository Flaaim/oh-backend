<?php

declare(strict_types=1);

use App\Ticket\Entity\Ticket;
use App\Ticket\Entity\TicketRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Psr\Container\ContainerInterface;

return [
    TicketRepository::class => function(ContainerInterface $container): TicketRepository {
        /** @var EntityManagerInterface $em */
        $em = $container->get(EntityManagerInterface::class);
        /** @var EntityRepository $repo */
        $repo = $em->getRepository(Ticket::class);
        return new TicketRepository($em, $repo);

    }
];