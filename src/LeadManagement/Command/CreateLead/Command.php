<?php

namespace App\LeadManagement\Command\CreateLead;

class Command
{
    public function __construct(
        public string $name,
        public string $contact,
        public string $message,
    ) {
    }
}
