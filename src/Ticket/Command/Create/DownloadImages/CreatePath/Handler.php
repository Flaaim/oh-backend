<?php

namespace App\Ticket\Command\Create\DownloadImages\CreatePath;

use App\Ticket\Service\ImageDownloader\PathManager;

class Handler
{
    public function __construct(private readonly PathManager $pathManager)
    {}
    public function handle(Command $command): void
    {
        $this->pathManager->forTicket(
            $command->ticket->getId()->getValue()
        )->create();
    }
}