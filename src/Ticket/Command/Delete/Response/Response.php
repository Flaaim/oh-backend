<?php

namespace App\Ticket\Command\Delete\Response;

class Response
{
    public function __construct(public readonly string $ticketId)
    {}
}
