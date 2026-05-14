<?php

declare(strict_types=1);

namespace App\Recipient\Command\Add;

final class Command
{
    public function __construct(
        public string $email
    ) {
    }
}
