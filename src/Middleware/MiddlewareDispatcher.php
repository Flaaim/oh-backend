<?php

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class MiddlewareDispatcher
{
    private RequestHandlerInterface $coreHandler;
    public function __construct(RequestHandlerInterface $handler){
        $this->coreHandler = $handler;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return $this->coreHandler->handle($request);
    }

    public function addMiddleware(MiddlewareInterface $middleware): self
    {
        $next = $this->coreHandler;

        $this->coreHandler = new MiddlewareRequestHandler($middleware, $next);
        return $this;
    }

}