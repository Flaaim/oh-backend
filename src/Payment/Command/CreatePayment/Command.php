<?php

namespace App\Payment\Command\CreatePayment;

use Webmozart\Assert\Assert;

class Command
{
    public function __construct(
        public string $email,
        public string $productId
    )
    {
        Assert::uuid($this->productId);
        Assert::email($this->email);
    }
}