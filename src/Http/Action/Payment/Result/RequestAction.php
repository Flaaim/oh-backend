<?php

namespace App\Http\Action\Payment\Result;

use App\Http\JsonResponse;
use App\Payment\Command\GetPaymentResult\Command;
use App\Payment\Command\GetPaymentResult\Handler;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;


class RequestAction implements RequestHandlerInterface
{
    private Handler $handler;
    public function __construct(Handler $handler)
    {
        $this->handler = $handler;
    }
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        try{
            $data = $request->getParsedBody() ?? [];

            if(empty($data['returnToken'])){
                throw new \Exception('Return token is empty');
            }

            $command = new Command($data['returnToken']);
            $response = $this->handler->handle($command);

            return new JsonResponse($response, 200);
        }catch (\DomainException $e){
            return new JsonResponse(['message' => $e->getMessage()], 400);
        } catch (\Exception $e){
            return new JsonResponse(['message' => $e->getMessage()], 500);
        }

    }
}