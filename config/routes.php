<?php

declare(strict_types=1);

use App\Http\Action\Auth\GetToken;
use App\Http\Action\Payment;
use App\Http\Action\Product\Upload;
use App\Http\Action\Product\Upsert;
use App\Http\Middleware\AuthMiddleware;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;

return static function(App $app): void {

    $app->group('/payment-service', function (RouteCollectorProxy $group): void {
        $group->post('/process-payment', Payment\CreatePayment\RequestAction::class);
        $group->post('/payment-webhook', Payment\HookPayment\RequestAction::class);
        $group->post('/result', Payment\Result\RequestAction::class);


        $group->group('/products', function (RouteCollectorProxy $group): void {
            $group->post('/upsert', Upsert\RequestAction::class);
            $group->post('/upload', Upload\RequestAction::class);
        })->add(AuthMiddleware::class);

        $group->group('/auth', function (RouteCollectorProxy $group): void {
            $group->post('/login', GetToken\RequestAction::class);
        });

    });



};