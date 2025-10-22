<?php

namespace App\Http\Action\Payment\HookPayment;

use App\Http\EmptyResponse;
use App\Http\JsonResponse;
use App\Payment\Command\HookPayment\Command;
use App\Payment\Command\HookPayment\Handler;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class RequestAction
{
    public function __construct(private readonly ContainerInterface $container)
    {}
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        if($request->getMethod() !== 'POST') {
            return new JsonResponse(['message' => 'Method not allowed'], 405);
        }

        try {
            $data = $request->getParsedBody() ?? [];

            if(json_last_error() !== JSON_ERROR_NONE) {
                return new JsonResponse(['message' => json_last_error_msg()], 400);
            }

            $command = new Command($data);

            $handler = $this->container->get(Handler::class);
            /** @var Handler $handler */
            $handler->handle($command);
            return new EmptyResponse();
        }catch (\RuntimeException|\Exception $e){
            return new JsonResponse(['message' => $e->getMessage()], 500);
        }
    }
}