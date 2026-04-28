final <?php

namespace App\Http\Action\Access\GetAccess;

use App\Access\Command\GetAccess\Command;
use App\Access\Command\GetAccess\Handler;
use App\Http\JsonResponse;
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
    #[\Override]
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $encodedToken = $request->getQueryParams()['token'] ?? '';


        $command = new Command(
            $encodedToken
        );

        $this->validator->validate($command);

        $accessDto = $this->handler->handle($command);

        return new JsonResponse($accessDto);
    }
}