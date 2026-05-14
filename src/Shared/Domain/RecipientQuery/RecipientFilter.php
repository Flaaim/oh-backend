<?php

declare(strict_types=1);

namespace App\Shared\Domain\RecipientQuery;

final class RecipientFilter
{
    public function __construct(
        public ?bool $isActive = true,
        public ?array $categories = null
    ) {
    }
}
