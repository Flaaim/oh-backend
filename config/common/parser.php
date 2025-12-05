<?php

declare(strict_types=1);


use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Slim\Psr7\Factory\ResponseFactory;

return [
    'hosts' => [
        'http://olimpoks.chukk.ru:82/',
        'https://olimpoks.gtk-nk.ru/',
        'https://olimpoks.hydroschool.ru/',
        'https://gpn.olimpoks.ru/'
    ],
    'basePath' => __DIR__ . '/../../public/QuestionImages',
    'urlPath' => 'http://localhost/QuestionImages',
    'ParserAuth' => [
        'Login' => getenv('PARSER_LOGIN'),
        'Password' => getenv('PARSER_PASSWORD'),
    ],
    ResponseFactoryInterface::class => Di\get(ResponseFactory::class),

    HostMapper::class => function (ContainerInterface $container) {
        $config = $container->get('config');
        return new HostMapper($config['hosts']);
    },
    Auth::class => function (ContainerInterface $container) {
        $config = $container->get('config')['ParserAuth'];
        return new Auth($config['Login'], $config['Password']);
    },
    PathManager::class => function (ContainerInterface $container) {
        return new PathManager($container->get('config')['basePath']);
    },
    UrlBuilder::class => function (ContainerInterface $container) {
        return new UrlBuilder($container->get('config')['urlPath']);
    }
];