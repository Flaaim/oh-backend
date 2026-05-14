<?php

declare(strict_types=1);

namespace App\Product\Command\RecountPrice;

use Symfony\Component\Validator\Constraints as Assert;

final class Command
{
    public function __construct(
        #[Assert\Choice(choices: ['file', 'access'])]
        public readonly string $type,
        #[Assert\NotBlank]
        #[Assert\Uuid]
        public readonly string $productId,
    ) {
    }
}
