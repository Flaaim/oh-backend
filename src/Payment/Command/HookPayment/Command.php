<?php

declare(strict_types=1);

namespace App\Payment\Command\HookPayment;

final class Command
{
    public function __construct(
        public readonly array $data
    ) {
    }
}
