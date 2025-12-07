<?php

namespace App\Ticket\Command\Create;

use Symfony\Component\Validator\Constraints as Assert;

class Command
{
    public function __construct(
        #[Assert\NotBlank]
        public readonly array $ticket
    )
    {

    }
}
