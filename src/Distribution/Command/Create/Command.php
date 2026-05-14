<?php

declare(strict_types=1);

namespace App\Distribution\Command\Create;

use Symfony\Component\Validator\Constraints as Assert;

class Command
{
    public function __construct(
        #[Assert\NotBlank]
        public string $subject,
        #[Assert\NotBlank]
        public string $templateId,
    ) {
    }
}
