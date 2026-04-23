<?php

namespace App\Recipient\Command\Add;

class Command
{
    public function __construct(
        public string $email
    ){
    }
}