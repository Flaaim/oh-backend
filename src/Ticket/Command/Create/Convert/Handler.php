<?php

namespace App\Ticket\Command\Create\Convert;

use App\Ticket\Service\ImageDownloader\PathConverter;

class Handler
{
    public function __construct(
        private readonly PathConverter $pathConverter
    )
    {

    }
    public function handle(Command $command): void
    {
        $ticket = $command->ticket;
        $result = $command->result;
        $this->pathConverter
            ->convertQuestionImages($ticket, $result['questions'])
            ->convertAnswerImages($ticket, $result['answers']);


    }
}