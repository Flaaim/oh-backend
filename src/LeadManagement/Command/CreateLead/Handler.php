<?php

namespace App\LeadManagement\Command\CreateLead;

use App\Shared\Domain\Service\Notification\TelegramNotifier;

class Handler
{
    public function __construct(
        private readonly TelegramNotifier $notifier,
    ) {
    }
    public function handle(Command $command): void
    {
        $this->notifier->sendLeadRequest($command->name, $command->contact, $command->message);
    }
}
