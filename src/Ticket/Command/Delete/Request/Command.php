<?php

namespace App\Ticket\Command\Delete\Request;

use Webmozart\Assert\Assert;

class Command
{
    public function __construct(public readonly string $ticketId){
        Assert::notEmpty($this->ticketId);
    }
}
