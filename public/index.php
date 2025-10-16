<?php

declare(strict_types=1);

use App\Http\Action\CreatePayment\RequestAction;
use App\Http\JsonResponse;
use Psr\Container\ContainerInterface;
use Slim\Psr7\Factory\ServerRequestFactory;

require __DIR__ . '/../vendor/autoload.php';

/** @var  ContainerInterface $container */
$container = require __DIR__ . '/../config/container.php';

$request = (new ServerRequestFactory())->createFromGlobals();

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type');

switch($request->getUri()->getPath()){
    case '/process-payment':
        $action = $container->get(RequestAction::class);
        /** @var RequestAction $action */
        $response = $action->handle($request);
        break;
    default:
        $response = new JsonResponse(['error' => 'Not found'], 404);
}

http_response_code($response->getStatusCode());
foreach ($response->getHeaders() as $name => $values) {
    foreach ($values as $value) {
        header(sprintf('%s: %s', $name, $value), false);
    }
}
echo $response->getBody();