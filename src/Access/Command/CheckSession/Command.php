<?php

declare(strict_types=1);

namespace App\Access\Command\CheckSession;

use Symfony\Component\Validator\Constraints as Assert;

final class Command
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Length(exactly: 22)]
        #[Assert\Regex(
            pattern: '/^[A-Za-z0-9_-]+$/',
            message: 'The format URL must be a valid.',
        )]
        public string $token,
        #[Assert\NotBlank]
        public string $sessionId,
    ) {
    }
}
