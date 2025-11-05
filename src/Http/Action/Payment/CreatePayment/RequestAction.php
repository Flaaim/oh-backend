<?php

namespace App\Http\Action\Payment\CreatePayment;

use App\Http\JsonResponse;
use App\Http\Validator\ValidationException;
use App\Http\Validator\Validator;
use App\Payment\Command\CreatePayment\Command;
use App\Payment\Command\CreatePayment\Handler;
use InvalidArgumentException;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;


class RequestAction implements RequestHandlerInterface
{
    public function __construct(
        private readonly ContainerInterface $container,
        private readonly Validator $validator
    )
    {}

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
            $email = $request->getParsedBody()['email'] ?? '';
            $productId = $request->getParsedBody()['productId'] ?? '';

            $command = new Command(
                $email,
                $productId
            );

            $this->validator->validate($command);

            /** @var Handler $handler */
            $handler = $this->container->get(Handler::class);
            $response = $handler->handle($command);

            return new JsonResponse($response, 201);

    }
}