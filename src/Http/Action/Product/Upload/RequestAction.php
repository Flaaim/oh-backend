<?php

namespace App\Http\Action\Product\Upload;

use App\Http\JsonResponse;
use App\Product\Command\Upload\Command;
use App\Product\Command\Upload\Handler;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class RequestAction implements RequestHandlerInterface
{
    public function __construct(private readonly ContainerInterface $container)
    {

    }
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $data = $request->getUploadedFiles()['file'] ?? [];
        $targetPath = $request->getParsedBody()['path'] ?? '';
        try{
            $command = new Command($data, $targetPath);
            /** @var Handler $handler */
            $handler = $this->container->get(Handler::class);
            $response = $handler->handle($command);
            return new JsonResponse($response);
        }catch (\Exception $e){
            return new JsonResponse(['message' => $e->getMessage()], 500);
        }
    }
}