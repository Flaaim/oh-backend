<?php

namespace App\Access\Entity\DTO;

class OpenAccessDTO
{
    public function __construct(
        public string $url,
        public string $name,
        public string $cipher
    ) {
    }
}
