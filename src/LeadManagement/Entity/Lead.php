final <?php

namespace App\LeadManagement\Entity;

class Lead
{
    public function __construct(
        private LeadId $leadId,
        private string $name,
        private string $contact,
        private string $message,
    ){
    }
}