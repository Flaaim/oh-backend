<?php

declare(strict_types=1);

use App\Product\Entity\UploadDir;
use App\Product\Service\FileHandler;
use App\Product\Service\ValidatePath;
use App\Shared\Domain\ValueObject\RootPath;
use Psr\Container\ContainerInterface;

return [
    FileHandler::class => fn (ContainerInterface $container) => new FileHandler($container->get(UploadDir::class)),
    UploadDir::class => fn () => new UploadDir(
        new RootPath(sys_get_temp_dir()),
        new ValidatePath()
    ),
];
