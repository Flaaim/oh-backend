<?php

declare(strict_types=1);

use App\Http\Action\CreatePayment;
use App\Http\JsonResponse;
use Psr\Container\ContainerInterface;
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

switch($request->getUri()->getPath()){
    case '/payment-service/process-payment':
        $action = $container->get(CreatePayment\RequestAction::class);
        $response = $action->handle($request);
        break;
    case '/payment-service/payment-webhook':
        $action = $container->get(HookPayment\RequestAction::class);
        /** @var HookPayment\RequestAction $action */
        $response = $action->handle($request);
        break;
    case '/payment-service/result':
        $action = $container->get(Result\RequestAction::class);
        /** @var Result\RequestAction $action */
        $response = $action->handle($request);
        break;
    default:
        $response = new JsonResponse(['message' => 'Not found'], 404);
        break;
}

http_response_code($response->getStatusCode());
foreach ($response->getHeaders() as $name => $values) {
    foreach ($values as $value) {
        header(sprintf('%s: %s', $name, $value), false);
    }
}
echo $response->getBody();