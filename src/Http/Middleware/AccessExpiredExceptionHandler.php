<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Access\Exception\AccessExpiredException;
use App\Http\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;

final class AccessExpiredExceptionHandler implements MiddlewareInterface
{
    public function __construct(
        private readonly LoggerInterface $logger,
    ) {
    }
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            return $handler->handle($request);
        } catch (AccessExpiredException $e) {
            $this->logger->info($e->getMessage(), [
                'message' => $e->getMessage(),
                'uri' => $request->getUri()->__toString(),
            ]);
            return new JsonResponse(
                [
                    'message' => $e->getMessage(),
                    'productId' => $e->getProductId(),
                ],
                410
            );
        }
    }
}
