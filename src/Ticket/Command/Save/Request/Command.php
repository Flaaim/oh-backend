<?php

namespace App\Ticket\Command\Save\Request;

use Webmozart\Assert\Assert;

class Command
{
    public function __construct(
        public readonly array $ticket
    )
    {
        Assert::isArray($this->ticket);
    }
}
