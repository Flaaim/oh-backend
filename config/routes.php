<?php

declare(strict_types=1);


use App\Http\Action\Auth\GetToken;
use App\Http\Action\Payment;
use App\Http\Action\Product;
use App\Http\Action\Access;
use App\Http\Action\LeadManagment;
use App\Http\Middleware\AccessExpiredExceptionHandler;
use App\Http\Middleware\AuthMiddleware;
use App\Http\Middleware\InitializeSessionMiddleware;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;

return static function(App $app): void {

    $app->group('/payment-service', function (RouteCollectorProxy $group): void {
        $group->post('/process-payment', Payment\CreatePayment\RequestAction::class);
        $group->post('/payment-webhook', Payment\HookPayment\RequestAction::class);
        $group->post('/result', Payment\Result\RequestAction::class);

        $group->group('/products', function (RouteCollectorProxy $group): void {
            $group->get('/get', Product\Get\RequestAction::class);
            $group->post('/recount-price', Product\RecountPrice\RequestAction::class);

            $group->post('/upsert', Product\Upsert\RequestAction::class)->add(AuthMiddleware::class);
            $group->post('/upload', Product\Upload\RequestAction::class)->add(AuthMiddleware::class);
        });

        $group->group('/access', function (RouteCollectorProxy $group): void {
            $group->get('/get', Access\GetAccess\RequestAction::class)
                ->add(AccessExpiredExceptionHandler::class)
                ->add(InitializeSessionMiddleware::class);

            $group->get('/stream-pdf', Access\Stream\RequestAction::class)->add(InitializeSessionMiddleware::class);
        });

        $group->group('/auth', function (RouteCollectorProxy $group): void {
            $group->post('/login', GetToken\RequestAction::class);
        });

        $group->group('/lead', function (RouteCollectorProxy $group): void {
            $group->get('/request', LeadManagment\CreateLead\RequestAction::class);
        });
    });



};