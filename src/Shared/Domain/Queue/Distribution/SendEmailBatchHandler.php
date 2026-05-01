<?php

namespace App\Shared\Domain\Queue\Distribution;

use App\Distribution\Entity\Distribution;
use App\Distribution\Entity\DistributionId;
use App\Distribution\Entity\DistributionRepository;
use App\Flusher;
use App\Recipient\Entity\Email;
use App\Shared\Domain\RecipientQuery\RecipientFilter;
use App\Shared\Domain\RecipientQuery\RecipientQueryInterface;
use App\Shared\Domain\Service\Distribution\DistributionInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class SendEmailBatchHandler
{
    private const BATCH_SIZE = 100;
    public function __construct(
        private readonly DistributionRepository $distributions,
        private readonly RecipientQueryInterface $recipientQuery,
        private readonly LoggerInterface $logger,
        private readonly DistributionInterface $uniSender,
        private readonly Flusher $flusher,
    ) {
    }
    public function handle(SendEmailBatchMessage $message): void
    {
        $distribution = $this->distributions->findById(new DistributionId($message->distributionId));

        if (null === $distribution) {
            throw new \RuntimeException('Distribution not found.');
        }

        $filter = new RecipientFilter(isActive: true);
        $batch = [];
        foreach ($this->recipientQuery->getIterable($filter) as $recipient) {
            /** @var array<Email> $recipient */
            $batch[] = ['email' => $recipient['email']->getValue()];

            if (count($batch) >= self::BATCH_SIZE) {
                $this->sendChunk($distribution, $batch);
                $batch = [];
            }
        }


        if (count($batch) > 0) {
            $this->sendChunk($distribution, $batch);
        }

        $distribution->ended();
        $this->flusher->flush();

        $this->logger->info('Distribution sent successfully');
    }

    private function sendChunk(Distribution $distribution, array $recipients): void
    {
        $this->uniSender->send(
            $distribution->getSubject(),
            $recipients,
            $distribution->getTemplateId()
        );
    }
}
