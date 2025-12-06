<?php

namespace App\Http\Action\Ticket\Create;


use App\Ticket\Command\Create\Request\Command;
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
        $ticketArray = $request->getParsedBody() ?? [];

        $command = new Command($ticketArray);


    }
}