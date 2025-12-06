<?php

namespace App\Http\Action\Ticket\Create;

use App\Http\EmptyResponse;
use App\Ticket\Command\Create\Command;
use App\Ticket\Command\Create\Request\Handler;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class RequestAction implements RequestHandlerInterface
{
    public function __construct(private readonly Handler $handler)
    {}
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $ticketArray = $request->getParsedBody() ?? [];

        $command = new Command($ticketArray);
        $this->handler->handle($command);


        return new EmptyResponse(201);
    }
}