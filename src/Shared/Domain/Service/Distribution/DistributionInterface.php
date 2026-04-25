<?php

namespace App\Shared\Domain\Service\Distribution;

interface DistributionInterface
{
    public function send(string $subject, array $recipients, string $templateId): void;
}