<?php

namespace App\Http\Action\Ticket\UpdateDetails;

use App\Http\EmptyResponse;
use App\Http\Validator\Validator;
use App\Ticket\Command\UpdateDetails\Command;
use App\Ticket\Command\UpdateDetails\Handler;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;


class RequestAction implements RequestHandlerInterface
{
    public function __construct(
        private readonly Handler $handler,
        private readonly Validator $validator
    )
    {}
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $body = $request->getParsedBody() ?? [];
        $id = $body['id'] ?? '';
        $name = $body['name'] ?? null;
        $cipher = $body['cipher'] ?? null;
        $updatedAt = $body['updatedAt'] ?? null;

        $command = new Command($id, $name, $cipher, $updatedAt);

        $this->validator->validate($command);

        $this->handler->handle($command);

        return new EmptyResponse();
    }
}