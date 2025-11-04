<?php

namespace App\Http\Action\Product\Upsert;


use App\Http\JsonResponse;
use App\Product\Command\Upsert\Command;
use App\Product\Command\Upsert\Handler;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class RequestAction implements RequestHandlerInterface
{
    public function __construct(
        private readonly ContainerInterface $container,
        private readonly ValidatorInterface $validator
    )
    {}
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $data = $request->getParsedBody() ?? [];

        $command = new Command(
            $data['name'] ?? '',
            $data['cipher'] ?? '',
            $data['amount'] ?? 0,
            $data['path'] ?? '',
            $data['course'] ?? ''
        );

        $violations = $this->validator->validate($command);
        if($violations->count() > 0){
            $errors = [];
            foreach ($violations as $violation) {
                /** @var ConstraintViolationInterface $violation */
                $errors[$violation->getPropertyPath()] = $violation->getMessage();
            }
            return new JsonResponse(['errors' => $errors], 422);
        }

        /** @var Handler $handler */
        $handler = $this->container->get(Handler::class);
        $response = $handler->handle($command);

        return new JsonResponse($response, 201);
    }
}