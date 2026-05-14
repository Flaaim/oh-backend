<?php

declare(strict_types=1);

namespace App\Shared\Domain\Service\Payment\DTO;

class PaymentCallbackDTO
{
    public function __construct(
        public array $rawData,
        public string $signature,
        public string $provider,
    ) {
    }
}
