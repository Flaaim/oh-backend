<?php

declare(strict_types=1);

namespace App\Product\Command\Upsert;

use Symfony\Component\Validator\Constraints as Assert;

final class Command
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Length(min: 5, max: 255)]
        public string $name,
        #[Assert\NotBlank]
        public string $cipher,
        #[Assert\Positive]
        public float $amount,
        #[Assert\NotBlank]
        public string $path,
        #[Assert\NotBlank]
        public string $course
    ) {
    }
}
