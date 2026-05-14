<?php

declare(strict_types=1);

namespace App\Shared\Domain\Queue\Distribution;

class SendEmailBatchMessage
{
    public function __construct(
        public string $distributionId,
    ) {
    }
}
