<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\FeatureToggle\FeatureSwitch;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class FeatureMiddleware implements MiddlewareInterface
{
    public function __construct(
        private readonly FeatureSwitch $features,
        private readonly string $header = 'X-Features'
    ) {
    }
    #[\Override]
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $header = $request->getHeaderLine($this->header);
        $features = array_filter(preg_split('/\s*,\s*/', $header));

        foreach ($features as $feature) {
            $this->features->enable($feature);
        }
        return $handler->handle($request);
    }
}
