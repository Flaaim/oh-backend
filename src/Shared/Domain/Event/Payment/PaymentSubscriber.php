<?php

declare(strict_types=1);

namespace App\Shared\Domain\Event\Payment;

use App\Recipient\Command\Add\Command as AddRecipientCommand;
use App\Shared\Domain\Service\Notification\TelegramNotifier;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class PaymentSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly TelegramNotifier $notifier,
        private readonly LoggerInterface $logger,
        private readonly MessageBusInterface $messageBus,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            SuccessfulPaymentEvent::class => [
                ['onSuccessPayment', 10],
                ['logSuccessfulPayment', 0],
            ],
        ];
    }
    public function onSuccessPayment(SuccessfulPaymentEvent $event): void
    {
        $this->notifier->sendSuccessfulPayment($event);
        $this->messageBus->dispatch(
            new AddRecipientCommand($event->getPayment()->getEmail()->getValue())
        );
    }

    public function logSuccessfulPayment(SuccessfulPaymentEvent $event): void
    {
        $payment = $event->getPayment();
        $this->logger->info('Successful Payment', ['payment' => [
            'email' => $payment->getEmail()->getValue(),
            'price' => $payment->getPrice()->getValue(),
            'productId' => $payment->getProductId(),
        ]]);
    }
}
