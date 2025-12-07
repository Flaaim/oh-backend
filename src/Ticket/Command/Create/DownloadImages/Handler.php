<?php

namespace App\Ticket\Command\Create\DownloadImages;

use App\Ticket\Service\ImageDownloader\ImageDownloader;
use App\Ticket\Command\Create\DownloadImages\CreatePath\Handler as CreatePathHandler;
use App\Ticket\Command\Create\DownloadImages\CreatePath\Command as CreatePathCommand;

class Handler
{
    public function __construct(
        private readonly CreatePathHandler   $createPathHandler,
        private readonly ImageDownloader     $imageDownloader,
    )
    {}
    public function handle(Command $command): array
    {
        $ticket = $command->ticket;

        $this->createPathHandler->handle(new CreatePathCommand($ticket));

        return $this->imageDownloader->download($ticket);
    }
}