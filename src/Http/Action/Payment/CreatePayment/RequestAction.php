<?php

namespace App\Http\Action\Payment\CreatePayment;

use App\Http\JsonResponse;
use App\Payment\Command\CreatePayment\Command;
use App\Payment\Command\CreatePayment\Handler;
use InvalidArgumentException;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;


class RequestAction implements RequestHandlerInterface
{
    public function __construct(private readonly ContainerInterface $container)
    {}

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        if ($request->getMethod() !== 'POST') {
            return new JsonResponse(['message' => 'Method not allowed'], 405);
        }
        try{
            $data = $request->getParsedBody() ?? [];

            if (!isset($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                throw new InvalidArgumentException('Invalid email address');
            }

            if (!isset($data['productId'])) {
                throw new InvalidArgumentException('Invalid product id');
            }

            $command = new Command(
                $data['email'],
                $data['productId']
            );

            /** @var Handler $handler */
            $handler = $this->container->get(Handler::class);
            $response = $handler->handle($command);

            return new JsonResponse($response, 201);
        }catch (InvalidArgumentException $e){
            return new JsonResponse(['message' => $e->getMessage()], 400);
        }catch (\DomainException $e){
            return new JsonResponse(['message' => $e->getMessage()], 404);
        } catch (\Exception $e){
            return new JsonResponse(['message' => $e->getMessage()], 500);
        }


    }
}