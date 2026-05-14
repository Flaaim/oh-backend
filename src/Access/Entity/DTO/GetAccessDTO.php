<?php

declare(strict_types=1);

namespace App\Access\Entity\DTO;

final class GetAccessDTO
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
