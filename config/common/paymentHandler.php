<?php

declare(strict_types=1);

use App\Payment\Command\CreatePayment\Handler as CreatePaymentHandler;

return [
    CreatePaymentHandler::class => DI\autowire(CreatePaymentHandler::class),
];