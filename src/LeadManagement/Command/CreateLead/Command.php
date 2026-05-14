<?php

declare(strict_types=1);

namespace App\LeadManagement\Command\CreateLead;

final class Command
{
    public function __construct(
        public string $name,
        public string $contact,
        public string $message,
    ) {
    }
}
