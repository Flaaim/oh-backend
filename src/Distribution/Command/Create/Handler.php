<?php

namespace App\Distribution\Command\Create;

use App\Distribution\Entity\Distribution;
use App\Distribution\Entity\DistributionId;
use App\Distribution\Entity\DistributionRepository;
use App\Flusher;
use App\Shared\Domain\Queue\Distribution\SendEmailBatchMessage;
use Symfony\Component\Messenger\MessageBusInterface;

class Handler
{
    public function __construct(
        private readonly DistributionRepository $distributions,
        private readonly Flusher $flusher,
        private readonly MessageBusInterface $messageBus,
    ) {
    }

    public function handle(Command $command): void
    {
        $distribution = new Distribution(
            DistributionId::generate(),
            $command->subject,
            $command->templateId,
        );

        $this->distributions->add($distribution);

        $this->flusher->flush();

        $this->messageBus->dispatch(new SendEmailBatchMessage($distribution->getId()->getValue()));
    }
}
