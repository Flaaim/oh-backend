<?php

namespace App\Http\Action\Result;

use App\Http\JsonResponse;
use App\Payment\Command\GetPaymentResult\Command;
use App\Payment\Command\GetPaymentResult\Handler;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;


class RequestAction
{
    private ContainerInterface $container;
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        if($request->getMethod() === '!POST') {
            return new JsonResponse(['message' => 'Not allowed'], 405);
        }
        try{
            $data = $request->getParsedBody();

            if (empty($data)) {
                $body = (string) $request->getBody();
                $data = json_decode($body, true) ?? [];
            }

            if(empty($data['returnToken'])){
                throw new \Exception('Return token is empty');
            }

            $command = new Command($data['returnToken']);
            $handler = $this->container->get(Handler::class);
            /** @var Handler $handler */
            $response = $handler->handle($command);
            return new JsonResponse($response, 200);
        }catch (\DomainException $e){
            return new JsonResponse(['message' => $e->getMessage()], 400);
        } catch (\Exception $e){
            return new JsonResponse(['message' => $e->getMessage()], 500);
        }

    }
}