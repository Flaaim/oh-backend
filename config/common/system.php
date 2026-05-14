<?php

declare(strict_types=1);

use App\Shared\Domain\ValueObject\BaseUrl;
use App\Shared\Domain\ValueObject\RootPath;
use Psr\Container\ContainerInterface;

return [
    'config' => [
        'debug' => (bool)getenv('APP_DEBUG'),
        'login' => getenv('AUTH_LOGIN'),
        'password' => getenv('AUTH_PASSWORD'),
        'template_paths' => __DIR__ . '/../../public/templates',
    ],
    RootPath::class => fn (ContainerInterface $container) => new RootPath(
        $container->get('config')['template_paths'],
    ),
    BaseUrl::class => function () {
        $baseUrl = getenv('LINK_ANSWERS');
        return new BaseUrl($baseUrl);
    },
];
