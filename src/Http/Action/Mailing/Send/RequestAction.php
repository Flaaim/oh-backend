<?php

namespace App\Http\Action\Mailing\Send;


use App\Mailing\Command\Send\Handler;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class RequestAction implements RequestHandlerInterface
{
    public function __construct(
        private readonly Handler $handler,
    ){

    }
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $this->handler->handle();
    }
}