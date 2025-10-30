<?php

declare(strict_types=1);

use App\Http\Middleware\ClearInputHandler;
use Psr\Container\ContainerInterface;
use Slim\App;
use Slim\Middleware\ErrorMiddleware;

return static function(App $app): void {
    $app->add(ClearInputHandler::class);
    $app->addBodyParsingMiddleware();
    $app->add(ErrorMiddleware::class);
};