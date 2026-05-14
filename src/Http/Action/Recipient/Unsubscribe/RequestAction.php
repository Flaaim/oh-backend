<?php

declare(strict_types=1);

namespace App\Http\Action\Recipient\Unsubscribe;

use App\Http\EmptyResponse;
use App\Http\Validator\Validator;
use App\Recipient\Command\Deactivate\Command;
use App\Recipient\Command\Deactivate\Handler;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class RequestAction implements RequestHandlerInterface
{
    public function __construct(
        private readonly Handler $handler,
        private readonly Validator $validator,
    ) {
    }
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $emails = $request->getParsedBody() ?? [];
        if (empty($emails)) {
            return new EmptyResponse(200);
        }
        $command = new Command($emails);

        $this->validator->validate($command);

        $this->handler->handle($command);

        return new EmptyResponse(200);
    }
}
