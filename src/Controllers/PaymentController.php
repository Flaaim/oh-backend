<?php

namespace App\Controllers;

use App\Payment\Command\CreatePayment\Command;
use App\Payment\Command\CreatePayment\Handler;
use Psr\Container\ContainerInterface;
use Ramsey\Uuid\Uuid;


class PaymentController
{
    private ContainerInterface $container;
    private Handler $handler;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->handler = $this->container->get(Handler::class);

    }
    public function create(): array
    {
        $command = new Command(
            'test@app.ru',
            Uuid::uuid4()->toString(),
        );

        $response = $this->handler->handle($command);

        return [
            $response->amount,
            $response->currency,
            $response->status,
        ];
    }
}