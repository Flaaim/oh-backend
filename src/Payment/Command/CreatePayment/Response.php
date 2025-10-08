<?php

namespace App\Payment\Command\CreatePayment;

readonly class Response
{
    public function __construct(
        public float  $amount,
        public string $currency,
        public string $status,
    ){}
}