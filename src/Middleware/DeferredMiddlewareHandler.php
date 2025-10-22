<?php

namespace App\Middleware;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class DeferredMiddlewareHandler implements RequestHandlerInterface
{
    private ContainerInterface $container;
    private string $middleware;
    private RequestHandlerInterface $next;
    public function __construct(string $middleware, RequestHandlerInterface $next, ContainerInterface $container)
    {
        $this->middleware = $middleware;
        $this->container = $container;
        $this->next = $next;
    }
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $resolved = $this->middleware;
        $instance = $this->container->get($resolved);

        if(!$instance instanceof MiddlewareInterface){
            throw new \Exception(sprintf('Middleware "%s" must implement MiddlewareInterface', $this->middleware));
        }
        return $instance->process($request, $this->next);
    }
}