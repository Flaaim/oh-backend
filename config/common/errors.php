<?php

declare(strict_types=1);

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Slim\Interfaces\CallableResolverInterface;
use Slim\Middleware\ErrorMiddleware;

return [
  ErrorMiddleware::class => function (ContainerInterface $container) {

    $callableResolver = $container->get(CallableResolverInterface::class);

    $responseFactory = $container->get(ResponseFactoryInterface::class);

    $config = $container->get('config')['errors'];

    return new ErrorMiddleware(
        $callableResolver,
        $responseFactory,
        $config['display_details'],
        $config['log'],
        true
    );

  },
    'config' => [
        'errors' => [
            'display_details' => (bool)getenv('APP_DEBUG'),
            'log' => true
        ]
    ]
];