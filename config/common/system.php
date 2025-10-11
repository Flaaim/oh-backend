<?php


declare(strict_types=1);


return [
    'config' => [
        'debug' => getenv('APP_DEBUG'),
        'template_paths' => __DIR__ . '/../../public/templates',
    ]
];