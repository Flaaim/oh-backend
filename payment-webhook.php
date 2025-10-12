<?php

declare(strict_types=1);

use App\Payment\Command\HookPayment\Command;
use App\Payment\Command\HookPayment\Handler;

require __DIR__ . '/vendor/autoload.php';

$container = require __DIR__ . '/config/container.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

if($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

try{

    $input = file_get_contents('php://input');

    $command = new Command($input);

    /** @var Handler $handler */
    $handler = $container->get(Handler::class);

    $handler->handle($command);

}catch (\Exception $e){
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}