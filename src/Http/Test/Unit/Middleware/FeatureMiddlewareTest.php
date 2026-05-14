<?php

declare(strict_types=1);

namespace App\Http\Test\Unit\Middleware;

use App\FeatureToggle\Features;
use App\FeatureToggle\FeatureSwitch;
use App\Http\Middleware\FeatureMiddleware;
use PHPUnit\Framework\TestCase;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Factory\ResponseFactory;
use Slim\Psr7\Factory\ServerRequestFactory;

class FeatureMiddlewareTest extends TestCase
{
    public function testEmpty(): void
    {
        $switch = $this->createMock(FeatureSwitch::class);
        $switch->expects($this->never())->method('enable');

        $middleware = new FeatureMiddleware($switch, 'X-Features');

        $request = (new ServerRequestFactory())->createServerRequest('GET', '/test');

        $handler = $this->createStub(RequestHandlerInterface::class);
        $handler->method('handle')->willReturn($source = (new ResponseFactory())->createResponse());

        $response = $middleware->process($request, $handler);

        self::assertSame($source, $response);
    }

    public function testWithFeatures(): void
    {
        $switch = $this->createMock(FeatureSwitch::class);
        $switch->expects($this->exactly(2))->method('enable')
            ->willReturnCallback(function (string $feature): void {
                static $i = 0;
                $i++;
                match ($i) {
                    1 => $this->assertEquals('ONE', $feature),
                    2 => $this->assertEquals('TWO', $feature),
                };
            });

        $middleware = new FeatureMiddleware($switch, 'X-Features');
        $request = (new ServerRequestFactory())
            ->createServerRequest('GET', '/test')->withHeader('X-Features', 'ONE, TWO');

        $handler = $this->createStub(RequestHandlerInterface::class);
        $handler->method('handle')->willReturn($source = (new ResponseFactory())->createResponse());

        $response = $middleware->process($request, $handler);

        self::assertSame($source, $response);
    }
}
