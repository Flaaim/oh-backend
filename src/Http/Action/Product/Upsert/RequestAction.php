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
use Test\Functional\Json;

class RequestAction implements RequestHandlerInterface
{
    public function __construct(private readonly ContainerInterface $container)
    {}
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        try{
            $data = $request->getParsedBody() ?? [];

            if(empty($data)){
                throw new \DomainException('Invalid request body');
            }

            $command = new Command($data['name'], $data['cipher'], $data['amount'], $data['path'], $data['course']);
            /** @var Handler $handler */
            $handler = $this->container->get(Handler::class);
            $response = $handler->handle($command);

            return new JsonResponse($response, 201);
        }catch (\Exception $e){
            return new JsonResponse(['message' => $e->getMessage()], 500);
        }



    }
}