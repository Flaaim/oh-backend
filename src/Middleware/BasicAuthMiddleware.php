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
        $log = __DIR__ . '/../../var/log/oh-backend.log';
        file_put_contents($log, "Auth skipped for debugging\n", FILE_APPEND);

        // TODO: временно отключено для отладки
        // $login = $this->container->get('config')['login'];
        // $password = $this->container->get('config')['password'];
        // $expectedAuth = "Basic " . base64_encode("$login:$password");
        //
        // if($expectedAuth !== $request->getHeaderLine('Authorization')) {
        //     return new JsonResponse(['message' => 'Unauthorized'], 401);
        // }
        return $handler->handle($request);
    }
}