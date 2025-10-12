<?php


declare(strict_types=1);


use App\Product\Entity\TemplatePath;
use Psr\Container\ContainerInterface;

return [
    'config' => [
        'debug' => getenv('APP_DEBUG'),
        'template_paths' => __DIR__ . '/../../public/templates',
    ],
    TemplatePath::class => function (ContainerInterface $container) {
        return new TemplatePath(
            $container->get('config')['template_paths'],
        );
    }
];