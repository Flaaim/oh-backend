<?php

namespace App\Access\Command\SyncSession;

use Symfony\Component\Validator\Constraints as Assert;

class Command
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
        #[Assert\NotBlank]
        public string $ip,
        #[Assert\NotBlank]
        public string $userAgent,
    )
    {}
}