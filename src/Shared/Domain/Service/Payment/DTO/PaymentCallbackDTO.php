<?php

namespace App\Shared\Domain\Service\Payment\DTO;

readonly class PaymentCallbackDTO
{
    public function __construct(
        public array  $rawData,
        public string $signature,
        public string $provider,
    ) {}
}
