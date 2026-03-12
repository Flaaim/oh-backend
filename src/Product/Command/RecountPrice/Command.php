<?php

namespace App\Product\Command\RecountPrice;

use Symfony\Component\Validator\Constraints as Assert;

class Command
{
    public function __construct(
        #[Assert\Choice(choices: ['file', 'access'])]
        public readonly string $type,
        #[Assert\NotBlank]
        #[Assert\Uuid]
        public readonly string $productId,
    ){
    }
}