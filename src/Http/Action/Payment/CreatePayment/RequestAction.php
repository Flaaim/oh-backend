final <?php

namespace App\Http\Action\Payment\CreatePayment;

use App\Http\JsonResponse;
use App\Http\Validator\Validator;
use App\Payment\Command\CreatePayment\Command;
use App\Payment\Command\CreatePayment\Handler;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;


class RequestAction implements RequestHandlerInterface
{
    public function __construct(
        private readonly Handler $handler,
        private readonly Validator $validator
    )
    {}

    #[\Override]
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
            $email = $request->getParsedBody()['email'] ?? '';
            $productId = $request->getParsedBody()['productId'] ?? '';
            $type = $request->getParsedBody()['type'] ?? '';

            $command = new Command(
                $email,
                $productId,
                $type
            );

            $this->validator->validate($command);

            $response = $this->handler->handle($command);

            return new JsonResponse($response, 201);

    }
}