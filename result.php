<?php

declare(strict_types=1);

use App\Payment\Command\GetPaymentResult\Command;
use App\Payment\Command\GetPaymentResult\Handler;

require __DIR__.'/vendor/autoload.php';

$container = require __DIR__.'/config/container.php';

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

    if(empty($input['returnToken'])){
        throw new \Exception('Return token is invalid');
    }
    $returnToken = $input['returnToken'];

    $command = new Command($returnToken);

    $handler = $container->get(Handler::class);
    /** @var Handler $handler */
    $handler->handle($command);

    $response = $handler->handle($command);

    echo json_encode($response);
    exit;
}catch (\Exception $e){
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}