<?php

namespace App\Ticket\Command\Create\Request;

use App\Flusher;
use App\Ticket\Entity\Ticket;
use App\Ticket\Entity\TicketRepository;
use App\Ticket\Service\ImageDownloader\DownloadChecker;
use App\Ticket\Service\ImageDownloader\ImageDownloader;
use App\Ticket\Service\ImageDownloader\PathConverter;
use App\Ticket\Service\ImageDownloader\PathManager;


class Handler
{
    public function __construct(
        private readonly TicketRepository    $tickets,
        private readonly Flusher             $flusher,
        private readonly PathManager         $path,
        private readonly DownloadChecker     $downloadChecker,
        private readonly ImageDownloader     $imageDownloader,
        private readonly PathConverter       $pathConverter,
    )
    {}

    public function handle(Command $command): void
    {
        $ticket = Ticket::fromArray($command->ticket);

        if($this->downloadChecker->shouldDownload($ticket)) {
            $this->path->forTicket($ticket->getId()->getValue())->create();
            $result = $this->imageDownloader->download($ticket);
            $this->pathConverter
                ->convertQuestionImages($ticket, $result['questions'])
                ->convertAnswerImages($ticket, $result['answers']);
        }
        $this->tickets->addOrUpdate($ticket);

        $this->flusher->flush();
    }
}
