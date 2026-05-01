<?php

namespace App\Shared\Infrastructure\Distribution;

use App\Shared\Domain\Service\Distribution\DistributionInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Log\LoggerInterface;

class UniSender implements DistributionInterface
{
    public function __construct(
        private Client $client,
        private LoggerInterface $logger,
        private string $apiKey
    ) {
    }
    public function send(string $subject, array $recipients, string $templateId): void
    {
        $requestBody =  [
            "message" => [
                'recipients' => $recipients,
                "template_id" => $templateId,
                "skip_unsubscribe" => 0,
                "subject" => $subject,
            ],
        ];

        try {
            $this->client->request('POST', 'email/send.json', [
                'headers' => [
                    'X-API-KEY' => $this->apiKey,
                    'Content-Type' => 'application/json',
                ],
                'json' => $requestBody,
            ]);
        } catch (GuzzleException $e) {
            $this->logger->error('Error sending distribution' . $e->getMessage());
            throw $e;
        }
    }
}
