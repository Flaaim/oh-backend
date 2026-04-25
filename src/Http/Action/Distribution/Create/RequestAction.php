<?php

namespace App\Http\Action\Distribution\Create;


use App\Distribution\Command\Create\Command;
use App\Distribution\Command\Create\Handler;
use App\Http\EmptyResponse;
use App\Http\Validator\Validator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class RequestAction implements RequestHandlerInterface
{
    public function __construct(
        private readonly Handler $handler,
        private readonly Validator $validator
    ){

    }
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $subject = $request->getParsedBody()['subject'] ?? '';
        $templateId = $request->getParsedBody()['templateId'] ?? '';

        $command = new Command(
            $subject,
            $templateId,
        );
        $this->validator->validate($command);

        $this->handler->handle($command);

        return new EmptyResponse();
    }
}