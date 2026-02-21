<?php

declare(strict_types=1);

use App\Shared\Domain\ValueObject\RootPath;

return [
    RootPath::class => function () {
        return new RootPath(sys_get_temp_dir());
    },
];