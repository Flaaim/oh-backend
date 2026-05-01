<?php

namespace App\Shared\Domain\RecipientQuery;

class RecipientFilter
{
    public function __construct(
        public ?bool $isActive = true,
        public ?array $categories = null
    ) {
    }
}
