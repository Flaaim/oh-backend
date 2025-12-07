<?php

namespace App\Ticket\Command\Create\DownloadImages\CreatePath;

use App\Ticket\Entity\Ticket;

class Command
{
    public function __construct(public readonly Ticket $ticket)
    {}
}