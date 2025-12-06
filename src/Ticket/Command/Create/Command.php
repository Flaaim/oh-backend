<?php

namespace App\Ticket\Command\Create;

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
