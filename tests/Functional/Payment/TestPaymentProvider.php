<?php

declare(strict_types=1);

namespace Test\Functional\Payment;

use App\Shared\Domain\Service\Payment\DTO\MakePaymentDTO;
use App\Shared\Domain\Service\Payment\DTO\PaymentCallbackDTO;
use App\Shared\Domain\Service\Payment\DTO\PaymentInfoDTO;
use App\Shared\Domain\Service\Payment\PaymentProviderInterface;

final class TestPaymentProvider implements PaymentProviderInterface
{
    public function initiatePayment(MakePaymentDTO $paymentData): PaymentInfoDTO
    {
        // TODO: Implement checkPaymentStatus() method.
    }

    public function handleCallback(PaymentCallbackDTO $callbackData): ?string
    {
        return 'hook_test_payment_id';
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
        return 'Yookassa';
    }
}
