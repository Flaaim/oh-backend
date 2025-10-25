<?php

declare(strict_types=1);

use App\Middleware\BasicAuthMiddleware;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;
use App\Http\Action\Payment;
use App\Http\Action\Product\Upsert;

return static function(App $app): void {

    $app->group('/payment-service', function (RouteCollectorProxy $group): void {
        $group->post('/process-payment', Payment\CreatePayment\RequestAction::class);
        $group->post('/payment-webhook', Payment\HookPayment\RequestAction::class);
        $group->post('/result', Payment\Result\RequestAction::class);


        $group->group('/products', function (RouteCollectorProxy $group): void {
            $group->post('/upsert', Upsert\RequestAction::class);
        });
    });



};