<?php

declare(strict_types=1);

namespace App\Http\Action\Home;

use App\FeatureToggle\FeatureFlag;
use App\FeatureToggle\Features;
use App\Http\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * @psalm-suppress UnusedClass
 */
class HomeAction implements RequestHandlerInterface
{
    public function __construct(
        private readonly FeatureFlag $features
    ) {
    }

    #[\Override]
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        if ($this->features->isEnabled('new')) {
            return new JsonResponse(['name' => 'New feature']);
        }
        return new JsonResponse(['Hello World!']);
    }
}
