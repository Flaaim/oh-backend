<?php

declare(strict_types=1);

namespace App\Access\Entity\DTO;

final class OpenAccessDTO
{
    public function __construct(
        public string $url,
        public string $name,
        public string $cipher
    ) {
    }
}
