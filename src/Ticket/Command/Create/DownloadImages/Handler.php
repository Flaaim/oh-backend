<?php

namespace App\Ticket\Command\Create\DownloadImages;

use App\Ticket\Service\ImageDownloader\DownloadChecker;
use App\Ticket\Service\ImageDownloader\ImageDownloader;
use App\Ticket\Service\ImageDownloader\PathConverter;
use App\Ticket\Service\ImageDownloader\PathManager;

class Handler
{
    public function __construct(
        private readonly PathManager         $path,
        private readonly ImageDownloader     $imageDownloader,
    )
    {}
    public function handle(Command $command): array
    {
        $ticket = $command->ticket;

        $this->path->forTicket($ticket->getId()->getValue())->create();

        return $this->imageDownloader->download($ticket);
    }
}