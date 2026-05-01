<?php

namespace App\Http\Action\Access\Stream;

use App\Access\Command\Stream\Command;
use App\Access\Command\Stream\Handler;
use App\Http\FileResponse;
use App\Http\Validator\Validator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class RequestAction implements RequestHandlerInterface
{
    public function __construct(
        private readonly Handler $handler,
        private readonly Validator $validator
    ) {
    }
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $encodedToken = $request->getQueryParams()['token'] ?? '';
        $encodedProductId = $request->getQueryParams()['productId'] ?? '';

        $command = new Command($encodedToken, $encodedProductId);

        $this->validator->validate($command);

        $path = $this->handler->handle($command);

        return new FileResponse($path);
    }
}
