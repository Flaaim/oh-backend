<?php

namespace App\Http\Action\Payment\Result;

use App\Http\JsonResponse;
use App\Payment\Command\GetPaymentResult\Command;
use App\Payment\Command\GetPaymentResult\Handler;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;


class RequestAction implements RequestHandlerInterface
{
    public function __construct(private readonly Handler $handler)
    {}
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
            $data = $request->getParsedBody() ?? [];

            if(empty($data['returnToken'])){
                throw new \Exception('Return token is empty');
            }

            $command = new Command($data['returnToken']);

            $response = $this->handler->handle($command);

            return new JsonResponse($response, 200);

    }
}