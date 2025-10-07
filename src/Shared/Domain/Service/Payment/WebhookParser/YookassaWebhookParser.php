<?php

namespace App\Shared\Domain\Service\Payment\WebhookParser;

use App\Shared\Domain\Service\Payment\PaymentWebhookDataInterface;
use App\Shared\Domain\Service\Payment\PaymentWebhookParserInterface;
use InvalidArgumentException;


class YookassaWebhookParser implements PaymentWebhookParserInterface
{
    const PROVIDER_NAME = 'Yookassa';
    const ALLOWED_DATA = [
        'status',
        'paymentId',
        'amount',
        'currency',
        'metadata',
    ];
    public function supports(string $provider, array $data): bool
    {
        if ($provider !== self::PROVIDER_NAME) {
            return false;
        }
        foreach (self::ALLOWED_DATA as $requiredData) {
            if(!array_key_exists($requiredData, $data)) {
                return false;
            }
        }
        return true;
    }

    public function parse(array $data): PaymentWebhookDataInterface
    {
        foreach (self::ALLOWED_DATA as $requiredField) {
            if (!array_key_exists($requiredField, $data)) {
                throw new InvalidArgumentException("Missing required field: {$requiredField}");
            }
        }

        return new YookassaWebhookData(
            $data['status'],
            $data['amount'],
            $data['metadata'],
            $data['paymentId'],
            $data['currency']
        );
    }
}
