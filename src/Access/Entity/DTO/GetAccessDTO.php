<?php

namespace App\Access\Entity\DTO;

class GetAccessDTO
{
    public function __construct(
        public string $productId,
        public string $name,
        public string $cipher,
        public string $expiredAt,
        public string $email
    ) {
    }
}
