<?php

namespace App\Ticket\Command\Create\Convert;

use App\Ticket\Entity\Ticket;
use Webmozart\Assert\Assert;

class Command
{
    public function __construct(
        public readonly Ticket $ticket,
        public readonly array $result
    )
    {
        Assert::notEmpty($this->result);
    }
}