<?php

namespace App\Middleware;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use RuntimeException;

class MiddlewareDispatcher
{
    private RequestHandlerInterface $coreHandler;
    private ContainerInterface $container;
    public function __construct(RequestHandlerInterface $handler, ContainerInterface $container){
        $this->coreHandler = $handler;
        $this->container = $container;
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
    public function add($middleware): self
    {
        if($middleware instanceof MiddlewareInterface){
            return $this->addMiddleware($middleware);
        }

        if(is_string($middleware)){
            return $this->addDeferred($middleware);
        }

        throw new RuntimeException(
            'A middleware must be an object/class name referencing an implementation of ' .
            'MiddlewareInterface or a callable with a matching signature.'
        );
    }

    private function addDeferred(string $middleware): self
    {
        $next = $this->coreHandler;

        $this->coreHandler = new DeferredMiddlewareHandler($middleware, $next, $this->container);

        return $this;
    }
}