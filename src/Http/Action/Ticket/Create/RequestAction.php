<?php

namespace App\Http\Action\Ticket\Create;

use App\Http\EmptyResponse;
use App\Http\Validator\Validator;
use App\Ticket\Command\Create\Command;
use App\Ticket\Command\Create\Handler;
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
        $ticketArray = $request->getParsedBody() ?? [];

        $command = new Command($ticketArray);

        $this->validator->validate($command);

        $this->handler->handle($command);

        return new EmptyResponse(201);
    }
}