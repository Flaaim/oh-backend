<?php

namespace App\Http\Action\Telegram\SetWebhook;

use App\Http\JsonResponse;
use App\Http\Validator\Validator;
use App\TelegramBot\Command\SetWebhook\Command;
use App\TelegramBot\Command\SetWebhook\Handler;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class RequestAction implements RequestHandlerInterface
{
    public function __construct(
        private readonly Handler $handler,
        private readonly Validator $validator
    ){}
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $url = $request->getParsedBody()['url'] ?? '';

        $command = new Command($url);

        $this->validator->validate($command);

        $response = $this->handler->handle($command);

        return new JsonResponse($response);

    }
}