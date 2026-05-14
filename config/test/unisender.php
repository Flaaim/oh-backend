<?php

declare(strict_types=1);

use App\Shared\Domain\Service\Distribution\DistributionInterface;
use Test\Functional\Distribution\MemoryUniSender;

return [
    DistributionInterface::class => fn () => new MemoryUniSender(),
];
