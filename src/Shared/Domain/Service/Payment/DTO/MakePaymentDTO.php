<?php

namespace App\Shared\Domain\Service\Payment\DTO;

class MakePaymentDTO
{
    public function __construct(
        public float   $amount,
        public string  $currency,
        public string  $description,
        public array   $metadata = [], // Дополнительные данные (order_id, user_id и т.д.)
        public string  $returnToken,
        public ?string $customerEmail = null,

    ) {}
}
