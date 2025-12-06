<?php

namespace App\Ticket\Command\Create\Request;

use App\Flusher;
use App\Shared\Domain\Response\TicketResponse;
use App\Ticket\Entity\Ticket;
use App\Ticket\Entity\TicketRepository;
use App\Ticket\Service\ImageDownloader\DownloadChecker;
use App\Ticket\Service\ImageDownloader\ImageDownloader;
use App\Ticket\Service\ImageDownloader\PathConverter;
use App\Ticket\Service\ImageDownloader\PathManager;
use App\Ticket\Service\ImageDownloader\UrlBuilder;
use GuzzleHttp\ClientInterface;


class Handler
{
    public function __construct(
        private readonly TicketRepository   $tickets,
        private readonly Flusher            $flusher,
        private readonly PathManager        $path,
        private readonly UrlBuilder         $urlBuilder,
        private readonly ClientInterface    $client,
    )
    {}

    public function handle(Command $command): TicketResponse
    {
        $ticket = Ticket::fromArray($command->ticket);

        $downloadChecker = new DownloadChecker();

        if($downloadChecker->shouldDownload($ticket)) {
            $result = (new ImageDownloader(
                $this->path,
                $this->client,
                $ticket,
                $downloadChecker
            ))->download();

            (new PathConverter($this->urlBuilder))
                ->convertQuestionImages($ticket, $result['questions'])
                ->convertAnswerImages($ticket, $result['answers']);
        }

        $this->tickets->addOrUpdate($ticket);

        $this->flusher->flush();

        return TicketResponse::fromResult($ticket);
    }
}
