<?php

namespace App\Ticket\Command\Create;

use Symfony\Component\Validator\Constraints as Assert;

class Command
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Collection(
            fields: [
                'id' => new Assert\Required(
                    new Assert\Uuid()
                ),
                'cipher' => new Assert\Required([
                    new Assert\NotBlank(),
                    new Assert\Type('string'),
                ]),
                'name' => new Assert\Required([
                    new Assert\NotBlank(),
                    new Assert\Type('string'),
                ]),
                'updatedAt' => new Assert\Required([
                    new Assert\NotBlank(),
                    new Assert\Type('string'),
                ]),
                'status' => new Assert\Required([
                    new Assert\NotBlank(),
                    new Assert\Type('string'),
                ]),
                'questions' => new Assert\Required([
                    new Assert\NotBlank(),
                    new Assert\Type('array'),
                ]),
            ]
        )]
        public readonly array $ticket
    )
    {}
}
