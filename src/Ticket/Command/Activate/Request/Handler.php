<?php

namespace App\Ticket\Command\Activate\Request;

use App\Flusher;
use App\Shared\Domain\Response\TicketResponse;
use App\Shared\Domain\ValueObject\Id;
use App\Ticket\Entity\TicketRepository;

class Handler
{
    public function __construct(
        private readonly TicketRepository $tickets,
        private readonly Flusher $flusher
    )
    {}
    public function handle(Command $command): TicketResponse
    {
        $ticket = $this->tickets->getById(new Id($command->ticketId));

        $ticket->setActive();

        $this->tickets->addOrUpdate($ticket);

        $this->flusher->flush();

        return TicketResponse::fromResult($ticket);
    }
}
