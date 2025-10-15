<?php

namespace App\Payment\Command\GetPaymentResult;

use Webmozart\Assert\Assert;

class Command
{
    public function __construct(public string $returnToken)
    {
        Assert::uuid($this->returnToken);
    }
}