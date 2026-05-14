<?php

declare(strict_types=1);

namespace App\Access\Command\SyncSession;

use App\Access\Entity\Session;
use App\Access\Entity\SessionRepository;
use App\Access\Service\UuidConverter;
use App\Flusher;

class Handler
{
    public function __construct(
        private readonly SessionRepository $sessions,
        private readonly UuidConverter $uuidConverter,
        private readonly Flusher $flusher
    ) {
    }

    public function handle(Command $command): void
    {
        $decodedToken = $this->uuidConverter->decode($command->token);

        $session = $this->sessions->findByToken($decodedToken);

        if (!$session) {
            $this->sessions->create(
                new Session(
                    $decodedToken,
                    $command->sessionId,
                    $command->ip,
                    $command->userAgent,
                )
            );
            $this->flusher->flush();
            return;
        }

        if ($session->getSessionId() !== $command->sessionId) {
            $session->replace(
                $command->sessionId,
                $command->ip,
                $command->userAgent,
            );
        } else {
            $session->touch();
        }

        $this->flusher->flush();
    }
}
