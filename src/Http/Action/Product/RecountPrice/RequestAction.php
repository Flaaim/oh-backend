<?php

declare(strict_types=1);

namespace App\Http\Action\Product\RecountPrice;

use App\Http\JsonResponse;
use App\Http\Validator\Validator;
use App\Product\Command\RecountPrice\Command;
use App\Product\Command\RecountPrice\Handler;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class RequestAction implements RequestHandlerInterface
{
    public function __construct(
        private readonly Validator $validator,
        private readonly Handler $handler
    ) {
    }
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $productId = $request->getParsedBody()['productId'] ?? '';
        $type = $request->getParsedBody()['type'] ?? '';

        $command = new Command($type, $productId, );

        $this->validator->validate($command);

        $productDTO = $this->handler->handle($command);

        return new JsonResponse($productDTO);
    }
}
