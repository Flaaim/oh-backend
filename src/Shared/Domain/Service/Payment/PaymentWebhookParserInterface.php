<?php

declare(strict_types=1);

namespace App\Shared\Domain\Service\Payment;

interface PaymentWebhookParserInterface
{
    public function supports(string $provider, array $data): bool;
    public function parse(array $data): PaymentWebhookDataInterface;
}
