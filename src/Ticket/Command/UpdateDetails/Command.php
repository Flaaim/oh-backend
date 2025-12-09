<?php

namespace App\Ticket\Command\UpdateDetails;

use Symfony\Component\Validator\Constraints as Assert;

class Command
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Uuid]
        public readonly string $id,
        #[Assert\Length(max: 255)]
        public readonly ?string $name = null,
        #[Assert\Length(max: 50)]
        public readonly ?string $cipher = null,
        public readonly ?string $updatedAt = null,
    ){
    }
}