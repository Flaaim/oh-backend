final <?php

namespace App\Shared\Domain\Service\Payment\WebhookParser;

use App\Shared\Domain\Service\Payment\PaymentWebhookDataInterface;



class YookassaWebhookData implements PaymentWebhookDataInterface
{
    public function __construct(
        private readonly string $status,
        private readonly float $amount,
        private readonly array $metadata,
        private readonly string $paymentId,
        private readonly string $currency
    )
    {}
    #[\Override]
    public function getStatus(): string
    {
        return $this->status;
    }

    #[\Override]
    public function isPaid(): bool
    {
       return $this->status === 'succeeded';
    }

    #[\Override]
    public function getMetadata(string $key): ?string
    {
        return $this->metadata[$key] ?? null;
    }

    #[\Override]
    public function getPaymentId(): string
    {
        return $this->paymentId;
    }

    #[\Override]
    public function getAmount(): float
    {
        return $this->amount;
    }

    #[\Override]
    public function getCurrency(): string
    {
        return $this->currency;
    }
}
