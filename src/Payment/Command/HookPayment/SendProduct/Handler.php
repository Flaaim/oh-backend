<?php

declare(strict_types=1);

namespace App\Payment\Command\HookPayment\SendProduct;

use App\Payment\Entity\Payment;
use App\Payment\Entity\Status;
use App\Payment\Service\Delivery\DeliveryFactory;
use App\Shared\Domain\Event\Payment\SuccessfulPaymentEvent;
use App\Shared\Domain\Service\Payment\PaymentStatus;
use App\Shared\Domain\Service\Payment\PaymentWebhookDataInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;

class Handler
{
    public function __construct(
        private readonly DeliveryFactory $delivery,
        private readonly EventDispatcher $dispatcher
    ) {
    }
    public function handle(Command $command): void
    {
        if (!$this->shouldSendProduct($command->payment, $command->paymentWebHookData)) {
            return;
        }

        $this->delivery->createDelivery($command->paymentWebHookData);

        $command->payment->setSuccess(Status::succeeded());

        $event = new SuccessfulPaymentEvent($command->payment);

        $this->dispatcher->dispatch($event);
    }
    private function shouldSendProduct(Payment $payment, PaymentWebhookDataInterface $webhookData): bool
    {
        return !$payment->isSend() && $webhookData->isPaid() && $webhookData->getStatus() === PaymentStatus::SUCCEEDED;
    }
}
