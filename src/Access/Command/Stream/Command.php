<?php

namespace App\Access\Command\Stream;

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
        public string $encodedToken,
        #[Assert\NotBlank]
        public string $encodedProductId,
    ) {
    }
}
