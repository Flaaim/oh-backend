<?php

declare(strict_types=1);

use DI\ContainerBuilder;

$builder = new ContainerBuilder();

$builder->useAutowiring(true);

return $builder->build();