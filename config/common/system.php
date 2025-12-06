<?php


declare(strict_types=1);


use App\Shared\Domain\Service\Template\TemplatePath;
use App\Ticket\Service\ImageDownloader\PathManager;
use App\Ticket\Service\ImageDownloader\UrlBuilder;
use Psr\Container\ContainerInterface;

return [
    'config' => [
        'debug' => (bool)getenv('APP_DEBUG'),
        'login' => getenv('AUTH_LOGIN'),
        'password' => getenv('AUTH_PASSWORD'),
        'template_paths' => __DIR__ . '/../../public/templates',
        'basePath' => __DIR__ . '/../../public/QuestionImages',
        'urlPath' => 'http://localhost/QuestionImages',
    ],
    TemplatePath::class => function (ContainerInterface $container) {
        return new TemplatePath(
            $container->get('config')['template_paths'],
        );
    },
    PathManager::class => function (ContainerInterface $container) {
        return new PathManager($container->get('config')['basePath']);
    },
    UrlBuilder::class => function (ContainerInterface $container) {
        return new UrlBuilder($container->get('config')['urlPath']);
    }

];