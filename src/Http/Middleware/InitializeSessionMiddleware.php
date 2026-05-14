<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Access\Command\SyncSession\Command;
use App\Access\Command\SyncSession\Handler;
use App\Http\Validator\Validator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class InitializeSessionMiddleware implements MiddlewareInterface
{
    public function __construct(
        private readonly Handler $checkSessionHandler,
        private readonly Validator $validator,
    ) {
    }
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $encodedToken = $request->getQueryParams()['token'] ?? '';
        $cookies = $request->getCookieParams();
        $isNewSession = false;

        if (!isset($cookies['pdf_session'])) {
            $sessionId = bin2hex(random_bytes(32));
            $isNewSession = true;
        } else {
            $sessionId = $cookies['pdf_session'];
        }

        $serverParams = $request->getServerParams();
        $ip = $serverParams['REMOTE_ADDR'] ?? '';
        $userAgent = $request->getHeaderLine('User-Agent');

        $command = new Command(
            $encodedToken,
            $sessionId,
            $ip,
            $userAgent,
        );

        $this->validator->validate($command);

        $this->checkSessionHandler->handle($command);

        $response = $handler->handle($request);

        if ($isNewSession) {
            return $response->withHeader(
                'Set-Cookie',
                "pdf_session={$sessionId}; Path=/; HttpOnly; SameSite=Lax"
            );
        }

        return $response;
    }
}
