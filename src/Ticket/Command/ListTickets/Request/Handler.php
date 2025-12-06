<?php

namespace App\Ticket\Command\ListTickets\Request;

use App\Ticket\Command\ListTickets\Response\ListTicketResponse;
use App\Ticket\Entity\TicketRepository;

class Handler
{
    public function __construct(private readonly TicketRepository $tickets)
    {}
    public function handle(Command $command): ListTicketResponse
    {
        $result = $this->tickets->findAllPaginated(
            searchQuery: $command->searchQuery,
            sortBy: $command->sortBy,
            sortOrder: $command->sortOrder,
            page: $command->page,
            perPage: $command->perPage
        );

        return ListTicketResponse::fromResult($result);

    }
}
