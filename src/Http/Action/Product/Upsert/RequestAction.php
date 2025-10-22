<?php

namespace App\Http\Action\Product\Upsert;

use App\Http\EmptyResponse;
use App\Http\JsonResponse;
use App\Product\Command\Upsert\Command;
use App\Product\Command\Upsert\Handler;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class RequestAction implements RequestHandlerInterface
{
    public function __construct(private readonly ContainerInterface $container)
    {}
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        if ($request->getMethod() !== 'POST') {
            return new JsonResponse(['message' => 'Method not allowed'], 405);
        }
        try{
            $data = $request->getParsedBody() ?? [];
            if(empty($data)){
                throw new \Exception('Invalid request body');
            }

            $command = new Command($data['name'], $data['cipher'], $data['amount'], $data['path']);
            /** @var Handler $handler */
            $handler = $this->container->get(Handler::class);
            $handler->handle($command);

            return new EmptyResponse(201);
        }catch (\Exception $e){
            return new JsonResponse(['message' => $e->getMessage()], 500);
        }



    }
}