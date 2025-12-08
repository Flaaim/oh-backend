<?php

namespace App\Ticket\Command\Create;

use App\Flusher;
use App\Ticket\Entity\Ticket;
use App\Ticket\Entity\TicketRepository;
use App\Ticket\Service\ImageDownloader\DownloadChecker;
use App\Ticket\Command\Create\DownloadImages\Command as DownloadImagesCommand;
use App\Ticket\Command\Create\DownloadImages\Handler as DownloadImagesHandler;
use App\Ticket\Command\Create\Convert\Handler as ConvertHandler;
use App\Ticket\Command\Create\Convert\Command as ConvertCommand;
class Handler
{
    public function __construct(
        private readonly TicketRepository       $tickets,
        private readonly Flusher                $flusher,
        private readonly DownloadChecker        $downloadChecker,
        private readonly DownloadImagesHandler  $downloadImagesHandler,
        private readonly ConvertHandler         $convertHandler,
    )
    {}
    public function handle(Command $command): void
    {
        $ticket = Ticket::fromArray($command->ticket);

        if($this->downloadChecker->shouldDownload($ticket)) {
            $result = $this->downloadImagesHandler->handle(new DownloadImagesCommand($ticket));
            $this->convertHandler->handle(new ConvertCommand($ticket, $result));
        }

        $this->tickets->addOrUpdate($ticket);

        $this->flusher->flush();
    }
}