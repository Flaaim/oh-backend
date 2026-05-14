<?php

declare(strict_types=1);

namespace App\Recipient\Command\Add;

use App\Flusher;
use App\Recipient\Entity\Email;
use App\Recipient\Entity\Recipient;
use App\Recipient\Entity\RecipientId;
use App\Recipient\Entity\RecipientRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class Handler
{
    public function __construct(
        private readonly RecipientRepository $recipients,
        private readonly Flusher $flusher
    ) {
    }
    public function handle(Command $command): void
    {

        $email = new Email($command->email);
        $recipient = $this->recipients->findByEmail($email);

        if ($recipient) {
            return;
        }

        $recipient = new Recipient(
            RecipientId::generate(),
            $email,
        );

        $this->recipients->add($recipient);

        $this->flusher->flush();
    }
}
