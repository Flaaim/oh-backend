<?php

declare(strict_types=1);

namespace App\Http\Test\Unit\Middleware;

use App\Access\Exception\AccessExpiredException;
use App\Http\Middleware\AccessExpiredExceptionHandler;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;
use Slim\Psr7\Factory\ServerRequestFactory;
use Test\Functional\Json;

/**
 * @internal
 */
final class AccessExpiredExceptionHandlerTest extends TestCase
{
    public function testSuccess(): void
    {
        $middleware = new AccessExpiredExceptionHandler($logger = $this->createMock(LoggerInterface::class));

        $request = (new ServerRequestFactory())->createServerRequest('GET', '/test');
        $handler = $this->createMock(RequestHandlerInterface::class);

        $handler->expects(self::once())->method('handle')->with(
            self::equalTo($request),
        )->willReturn($response = $this->createMock(ResponseInterface::class));

        self::assertEquals($response, $middleware->process($request, $handler));
    }

    public function testException(): void
    {
        $productId = '05a26742-6ca1-464c-bd08-c47154394b76';
        $middleware = new AccessExpiredExceptionHandler($logger = $this->createMock(LoggerInterface::class));

        $request = (new ServerRequestFactory())->createServerRequest('GET', '/test');

        $handler = $this->createMock(RequestHandlerInterface::class);
        $handler->expects(self::once())->method('handle')->with(
            self::equalTo($request),
        )->willThrowException($e = new AccessExpiredException($productId, 'Доступ к файлу истек...'));


        $response = $middleware->process($request, $handler);

        self::assertEquals(410, $response->getStatusCode());
        self::assertJson($body = (string)$response->getBody());
        $data = Json::decode($body);

        self::assertEquals([
            'productId' => $e->getProductId(),
            'message' => $e->getMessage(),
        ], $data);
    }
}
