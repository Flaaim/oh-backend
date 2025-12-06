<?php

namespace App\Ticket\Command\GetTicketDetails\Request;

use App\Shared\Domain\Response\TicketResponse;
use App\Shared\Domain\ValueObject\Id;
use App\Ticket\Entity\TicketRepository;

class Handler
{
    public function __construct(private readonly TicketRepository $tickets)
    {}
    public function handle(Command $command): TicketResponse
    {
        $ticket = $this->tickets->getById(new Id($command->ticketId));

        return TicketResponse::fromResult($ticket);
    }
}
