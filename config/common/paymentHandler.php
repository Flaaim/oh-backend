<?php

declare(strict_types=1);

use App\Payment\Command\CreatePayment\Handler;

return [

    Handler::class => DI\autowire(Handler::class),
];