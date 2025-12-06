<?php

namespace App\Ticket\Service\ImageRemover;

use App\Parser\Entity\Ticket\Ticket;
use App\Ticket\Service\ImageDownloader\PathManager;

class ImageRemover
{
    public function __construct(
        private readonly Ticket $ticket,
        private readonly PathManager $manager
    )
    {}

    public function delete(): void
    {
        $this->manager->deleteTicket(
            $this->ticket->getId()->getValue()
        );
    }
}
