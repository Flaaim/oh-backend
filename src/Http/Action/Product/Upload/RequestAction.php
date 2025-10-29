<?php

namespace App\Http\Action\Product\Upload;

use App\Http\JsonResponse;
use App\Product\Command\Upload\Command;
use App\Product\Command\Upload\Handler;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UploadedFileInterface;
use Psr\Http\Server\RequestHandlerInterface;
use RuntimeException;

class RequestAction implements RequestHandlerInterface
{
    public function __construct(private readonly ContainerInterface $container)
    {}
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $uploadedFile = $request->getUploadedFiles()['file'] ?? [];
        $targetPath = $request->getParsedBody()['path'] ?? '';
        try{
            if($uploadedFile instanceof UploadedFileInterface){
                $command = new Command($uploadedFile, $targetPath);
                /** @var Handler $handler */
                $handler = $this->container->get(Handler::class);
                $response = $handler->handle($command);
                return new JsonResponse($response);
            }
            return new JsonResponse(['message' => 'File upload failed'], 400);
        }catch (\Exception $e){
            return new JsonResponse(['message' => $e->getMessage()], 500);
        }
    }
}