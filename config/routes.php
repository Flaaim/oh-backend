<?php

declare(strict_types=1);

use Slim\App;
use Slim\Routing\RouteCollectorProxy;
use App\Http\Action\Payment\CreatePayment;
use App\Http\Action\Payment\HookPayment;
use App\Http\Action\Payment\Result;
use App\Http\Action\Product\Upsert;

return static function(App $app, ): void {
    $app->group('/payment-service', function (RouteCollectorProxy $group): void {
        $group->post('/process-payment', CreatePayment\RequestAction::class);
        $group->post('/payment-webhook', HookPayment\RequestAction::class);
        $group->post('/result', Result\RequestAction::class);
    });

    $app->group('/products', function (RouteCollectorProxy $group): void {
       $group->post('/upsert', Upsert\RequestAction::class);
    });

};