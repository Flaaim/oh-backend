<?php

declare(strict_types=1);

namespace App\LeadManagement\Entity;

final class Lead
{
    public function __construct(
        private LeadId $leadId,
        private string $name,
        private string $contact,
        private string $message,
    ) {
    }
}
