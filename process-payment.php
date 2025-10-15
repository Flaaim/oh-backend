<?php

declare(strict_types=1);

use App\Payment\Command\CreatePayment\Command;
use App\Payment\Command\CreatePayment\Handler;
use Psr\Container\ContainerInterface;


require __DIR__ . '/vendor/autoload.php';

/** @var  ContainerInterface $container */
$container = require __DIR__ . '/config/container.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

try{
    $input = json_decode(file_get_contents('php://input'), true);

    if (!isset($input['email']) || !filter_var($input['email'], FILTER_VALIDATE_EMAIL)) {
        throw new InvalidArgumentException('Invalid email address');
    }
    if(!isset($input['productId'])) {
        throw new InvalidArgumentException('Invalid product id');
    }
    $email = $input['email'];
    $productId = $input['productId'];

    $command = new Command(
        $email,
        $productId
    );
    /** @var Handler $handler */
    $handler = $container->get(Handler::class);

    $response = $handler->handle($command);
    echo json_encode($response);
    exit;
}catch (\Exception $e){
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
