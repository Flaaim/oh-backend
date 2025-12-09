<?php

namespace App\Ticket\Command\UpdateDetails;

use App\Flusher;
use App\Shared\Domain\ValueObject\Id;
use App\Ticket\Entity\TicketRepository;

class Handler
{
    public function __construct(
        private readonly TicketRepository $tickets,
        private readonly Flusher $flusher
    ){}

    public function handle(Command $command): void
    {
        $ticket = $this->tickets->getById(new Id($command->id));

        $ticket->updateDetails(
            $command->name,
            $command->cipher,
            $command->updatedAt,
        );

        $this->tickets->addOrUpdate($ticket);

        $this->flusher->flush();
    }
}