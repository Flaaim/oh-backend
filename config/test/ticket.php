<?php

declare(strict_types=1);


use App\Ticket\Service\ImageDownloader\PathManager;
use App\Ticket\Service\ImageDownloader\UrlBuilder;

return [
    PathManager::class => function () {
        return new PathManager(sys_get_temp_dir());
    },
    UrlBuilder::class => function () {
        return new UrlBuilder(sys_get_temp_dir());
    }
];