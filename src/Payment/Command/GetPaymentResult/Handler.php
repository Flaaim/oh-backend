<?php

namespace App\Payment\Command\GetPaymentResult;

use App\Payment\Entity\PaymentRepository;

class Handler
{
    public function __construct(
        private readonly PaymentRepository $payments
    )
    {}

    public function handle(Command $command): Response
    {
        $payment = $this->payments->getByToken($command->returnToken);

        return new Response(
            $payment->getReturnToken()->getValue(),
            $payment->getStatus()->getValue(),
            $payment->getEmail()->getValue()
        );
    }
}