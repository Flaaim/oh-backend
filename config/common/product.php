<?php

declare(strict_types=1);

use App\Product\Entity\ProductRepository;
use App\Product\Query\ProductQuery;
use App\Shared\Domain\ProductQuery\ProductQueryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;

return [
    ProductQueryInterface::class => function (ContainerInterface $c): ProductQueryInterface {
        $em = $c->get(EntityManagerInterface::class);

        $products = new ProductRepository($em);

        return new ProductQuery($products);
    },
];
