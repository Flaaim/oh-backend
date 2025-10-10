<?php

namespace App\Payment\Command\CreatePayment;

use App\Flusher;
use App\Payment\Entity\Email;
use App\Payment\Entity\Payment;
use App\Payment\Entity\PaymentRepository;
use App\Payment\Entity\Status;
use App\Product\Entity\Currency;
use App\Product\Entity\Price;
use App\Product\Entity\ProductRepository;
use App\Shared\Domain\Service\Payment\DTO\MakePaymentDTO;
use App\Shared\Domain\Service\Payment\PaymentException;
use App\Shared\Domain\Service\Payment\Provider\YookassaProvider;
use App\Shared\ValueObject\Id;
use DateTimeImmutable;
use Ramsey\Uuid\Uuid;

class Handler
{

    public function __construct(
        private readonly Flusher $flusher,
        private readonly ProductRepository $products,
        private readonly YookassaProvider $yookassaProvider,
        private readonly PaymentRepository $payments,
    )
    {}
    public function handle(Command $command): Response
    {
        $email = new Email($command->email);
        $product = $this->products->get(new Id($command->productId));

        $payment = new Payment(
            new Id(Uuid::uuid4()->toString()),
            $email,
            $command->productId,
            new Price($product->getPrice()->getValue(), new Currency('RUB')),
            new DateTimeImmutable(),
        );
        try {
            $paymentInfo = $this->yookassaProvider->initiatePayment(
                new MakePaymentDTO(
                    $payment->getPrice()->getValue(),
                    $payment->getPrice()->getCurrency()->getValue(),
                    'Описание продукта',
                    ['email' => $email->getValue(), 'productId' => $product->getId()->getValue()],
                )
            );
            $payment->setExternalId($paymentInfo->paymentId);
        }catch (PaymentException $e){
            $payment->setStatus(Status::cancelled());

            $this->payments->create($payment);

            $this->flusher->flush();
            throw $e;
        }

        $this->payments->create($payment);
        $this->flusher->flush();

        return new Response(
            $payment->getPrice()->getValue(),
            $payment->getPrice()->getCurrency()->getValue(),
            $payment->getStatus()->getValue(),
            $paymentInfo->redirectUrl,
        );

    }
}