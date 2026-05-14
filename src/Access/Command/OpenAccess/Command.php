<?php

declare(strict_types=1);

namespace App\Access\Command\OpenAccess;

class Command
{
    public function __construct(
        public string $email,
        public string $productId,
    ) {
    }
}
