<?php

declare(strict_types=1);

use App\Command\PaymentCommand;
use Symfony\Component\Console\Application;

require __DIR__ . '/../vendor/autoload.php';

$app = new Application();

$app->add(new PaymentCommand());

$app->run();