<?php

declare(strict_types=1);

use App\Http\Action\CreatePayment;
use App\Http\JsonResponse;
use App\Middleware\BodyParserMiddleware;
use App\Middleware\MiddlewareDispatcher;
use Psr\Container\ContainerInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Factory\ServerRequestFactory;
use App\Http\Action\HookPayment;
use App\Http\Action\Result;


require __DIR__ . '/../vendor/autoload.php';

/** @var  ContainerInterface $container */
$container = require __DIR__ . '/../config/container.php';

$request = (new ServerRequestFactory())->createFromGlobals();

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type');


$handler = new class($container) implements RequestHandlerInterface{
    private ContainerInterface $container;

    public function __construct(ContainerInterface $container){
        $this->container = $container;
    }
    public function handle(\Psr\Http\Message\ServerRequestInterface $request): \Psr\Http\Message\ResponseInterface
    {
        switch($request->getUri()->getPath()){
            case '/payment-service/process-payment':
                $action = $this->container->get(CreatePayment\RequestAction::class);
                $response = $action->handle($request);
                break;
            case '/payment-service/payment-webhook':
                $action = $this->container->get(HookPayment\RequestAction::class);
                /** @var HookPayment\RequestAction $action */
                $response = $action->handle($request);
                break;
            case '/payment-service/result':
                $action = $this->container->get(Result\RequestAction::class);
                /** @var Result\RequestAction $action */
                $response = $action->handle($request);
                break;
            default:
                $response = new JsonResponse(['message' => 'Not found'], 404);
                break;
        }
        return $response;
    }
};
$dispatcher = new MiddlewareDispatcher($handler, $container);
$dispatcher->add(BodyParserMiddleware::class);
$response = $dispatcher->handle($request);


http_response_code($response->getStatusCode());
foreach ($response->getHeaders() as $name => $values) {
    foreach ($values as $value) {
        header(sprintf('%s: %s', $name, $value), false);
    }
}
echo $response->getBody();