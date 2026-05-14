<?php

declare(strict_types=1);

namespace App\Recipient\Command\Deactivate;

use App\Flusher;
use App\Recipient\Entity\Recipient;
use App\Recipient\Entity\RecipientRepository;

final class Handler
{
    public function __construct(
        private RecipientRepository $recipients,
        private Flusher $flusher
    ) {
    }
    public function handle(Command $command): void
    {
        /** @var array<Recipient> $recipients */
        $recipients = $this->recipients->findAllByEmails($command->emails);

        if (empty($recipients)) {
            return;
        }

        foreach ($recipients as $recipient) {
            $recipient->deactivate();
        }

        $this->flusher->flush();
    }
}
