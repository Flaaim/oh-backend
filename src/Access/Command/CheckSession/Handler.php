<?php

namespace App\Access\Command\CheckSession;

use App\Access\Entity\SessionRepository;
use App\Access\Service\UuidConverter;


class Handler
{
    public function __construct(
        private readonly SessionRepository $sessions,
        private readonly UuidConverter $uuidConverter,
    )
    {}
    public function handle(Command $command): void
    {
        $decodedToken = $this->uuidConverter->decode($command->token);

        $session = $this->sessions->findByToken($decodedToken);

        if($session->getSessionId() !== $command->sessionId) {

        }
    }
}