<?php

namespace App\Ticket\Command\Delete\Request;

use App\Flusher;
use App\Service\Common\TransactionManager;
use App\Shared\Domain\ValueObject\Id;
use App\Ticket\Command\Delete\Response\Response;
use App\Ticket\Entity\TicketRepository;
use App\Ticket\Service\ImageDownloader\PathManager;
use App\Ticket\Service\ImageRemover\ImageRemover;

class Handler
{
    public function __construct(
        private readonly TicketRepository $tickets,
        private readonly Flusher $flusher,
        private readonly PathManager $pathManager,
        private readonly TransactionManager $transactionManager,
    )
    {}


    public function handle(Command $command): Response
    {
        return $this->transactionManager->transactional(function () use ($command) {
            $ticket = $this->tickets->getById(new Id($command->ticketId));

            (new ImageRemover(
                $ticket,
                $this->pathManager
            ))->delete();

            $this->tickets->remove($ticket);

            $this->flusher->flush();

            return new Response($ticket->getId()->getValue());
        });
    }
}
