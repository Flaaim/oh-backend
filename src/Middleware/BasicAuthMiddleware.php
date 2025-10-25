<?php

namespace App\Middleware;

use App\Http\JsonResponse;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class BasicAuthMiddleware implements MiddlewareInterface
{
    public function __construct(private readonly ContainerInterface $container)
    {
        
    }
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $login = $this->container->get('config')['login'];
        $password = $this->container->get('config')['password'];
        $log = __DIR__ . '/../../var/log/oh-backend.log';
        file_put_contents($log, "{$login}:{$password}\n", FILE_APPEND);
        $expectedAuth = "Basic " . base64_encode("$login:$password");
        file_put_contents($log, $expectedAuth, FILE_APPEND);
        if($expectedAuth !== $request->getHeaderLine('Authorization')) {
            return new JsonResponse([
                'message' => 'Unauthorized',
            ], 401);
        }
        return $handler->handle($request);
    }
}