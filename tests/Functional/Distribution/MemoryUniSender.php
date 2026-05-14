<?php

declare(strict_types=1);

namespace Test\Functional\Distribution;

use App\Shared\Domain\Service\Distribution\DistributionInterface;
use GuzzleHttp\Client;

class MemoryUniSender implements DistributionInterface
{
    private array $sentMessages = [];
    public bool $throwExceptions = false;
    public function send(string $subject, array $recipients, string $templateId): void
    {
        if ($this->throwExceptions) {
            throw new \DomainException('Exception when sending message');
        }
        $this->sentMessages[] = [
            'subject' => $subject,
            'recipients' => $recipients,
            'templateId' => $templateId,
        ];
    }
    public function getSentMessages(): array
    {
        return $this->sentMessages;
    }
    public function clear(): void
    {
        $this->sentMessages = [];
    }

    public function shouldFail(): void
    {
        $this->throwExceptions = true;
    }
}
