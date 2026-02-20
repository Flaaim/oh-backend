<?php

namespace App\Access\Command\GetAccess;

use App\Access\Entity\AccessRepository;


class Handler
{
    public function __construct(
        private readonly AccessRepository $accesses,

    ){
    }

    public function handle(Command $command): void
    {
        $token = $this->generator->decode($command->url);


    }
}