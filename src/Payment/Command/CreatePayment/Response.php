<?php

namespace App\Payment\Command\CreatePayment;

class Response
{
    public function __construct(
        private float $amount,
        private string $currency,
        private string $description,

    ){}
}