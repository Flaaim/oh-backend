<?php

namespace App\Http\Action\Product\Upload;

use App\Http\JsonResponse;
use App\Product\Command\Upload\Command;
use App\Product\Command\Upload\Handler;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RequestAction implements RequestHandlerInterface
{
    public function __construct(
        private readonly ContainerInterface $container,
        private readonly ValidatorInterface $validator,
    )
    {}
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $uploadedFile = $request->getUploadedFiles()['file'] ?? null;
        if(is_array($uploadedFile)) {
            return new JsonResponse(['errors' => [
                'multipleFiles' => 'Only one uploaded file is allowed',
            ]], 422);
        }
        $targetPath = $request->getParsedBody()['path'] ?? '';

        $command = new Command($uploadedFile, $targetPath);

        $violations = $this->validator->validate($command);
        if($violations->count() > 0){
            $errors = [];
            foreach ($violations as $violation){
                $errors[$violation->getPropertyPath()] = $violation->getMessage();
            }
            return new JsonResponse(['errors' => $errors], 422);
        }

        /** @var Handler $handler */
        $handler = $this->container->get(Handler::class);
        $response = $handler->handle($command);
        return new JsonResponse($response);




    }
}