<?php

namespace App\Ticket\Command\Activate\Request;

class Command
{
    public function __construct(
        public readonly string $ticketId
    ){}
}
