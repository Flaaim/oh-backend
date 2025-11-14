<?php

namespace App\Http\Action\Telegram\ProcessWebhook;

use App\Http\JsonResponse;
use App\Http\Validator\Validator;
use App\TelegramBot\Command\ProcessWebhook\Command;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use App\TelegramBot\Command\ProcessWebhook\Handler;

class RequestAction implements RequestHandlerInterface
{
    public function __construct(
        private readonly Handler $handler,
        private readonly Validator $validator
    )
    {}

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $body = $request->getParsedBody() ?? [];

        $command = new Command($body);
        $this->validator->validate($command);
        $response = $this->handler->handle($command);

        return new JsonResponse($response, 200);
    }
}