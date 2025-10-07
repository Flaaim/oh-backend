<?php

namespace App\Shared\Domain\Service\Payment\Provider;

use App\Shared\Domain\Service\Payment\DTO\MakePaymentDTO;
use App\Shared\Domain\Service\Payment\DTO\PaymentCallbackDTO;
use App\Shared\Domain\Service\Payment\DTO\PaymentInfoDTO;
use App\Shared\Domain\Service\Payment\PaymentException;
use App\Shared\Domain\Service\Payment\PaymentProviderInterface;
use YooKassa\Client;

class YookassaProvider implements PaymentProviderInterface
{
    public function __construct(
        private readonly Client $client,
        private readonly YookassaConfig $config
    )
    {}
    public function initiatePayment(MakePaymentDTO $paymentData): PaymentInfoDTO
    {
        $idempotenceKey = uniqid('', true);
        try{
            $response = $this->client->createPayment([
                'amount' => [
                    'value' => $paymentData->amount,
                    'currency' => $paymentData->currency,
                ],
                'confirmation' => [
                    'type' => 'redirect',
                    'locale' => 'ru_RU',
                    'return_url' => $this->config->getReturnUrl(),
                ],
                'capture' => true,
                'description' => $paymentData->description,
                'metadata' => $paymentData->metadata,
                'receipt' => [
                    'customer' => [
                        'email' => $paymentData->customerEmail,
                    ]
                ]
            ], $idempotenceKey);

            return new PaymentInfoDTO(
                $response->getId(),
                $response->getStatus(),
                $response->getConfirmation()->getConfirmationUrl()
            );
        }catch (\Exception $e){
            throw new PaymentException($e->getMessage());
        }
    }

    public function handleCallback(PaymentCallbackDTO $callbackData): bool
    {

    }

    public function checkPaymentStatus(string $paymentId): string
    {
        // TODO: Implement checkPaymentStatus() method.
    }

    public function getSupportedCurrencies(): array
    {
        // TODO: Implement getSupportedCurrencies() method.
    }

    public function getName(): string
    {
        return $this->config->getName();
    }
}
