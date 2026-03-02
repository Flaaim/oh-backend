<?php

namespace App\Http\Test\Unit\Middleware;

use App\Access\Command\CheckSession\Handler;
use App\Http\Middleware\CheckSessionMiddleware;
use App\Http\Validator\ValidationException;
use App\Http\Validator\Validator;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Factory\ResponseFactory;
use Slim\Psr7\Factory\ServerRequestFactory;
use Symfony\Component\Validator\Validation;
use Test\Functional\Json;

class CheckSessionMiddlewareTest extends TestCase
{

    public function testSuccess(): void
    {
        $checkSessionHandler = $this->createMock(Handler::class);
        $validator = $this->createMock(Validator::class);
        $queryParams = ['token' => 'FEFfOz8DSPyKu0NTU9pKxQ'];
        $cookieParams = ['pdf_session' => bin2hex(random_bytes(32))];


        $middleware = new CheckSessionMiddleware($checkSessionHandler, $validator);

        $request = (new ServerRequestFactory())->createServerRequest('GET', '/test', [
            'REMOTE_ADDR' => '127.0.0.1',
        ])
            ->withQueryParams($queryParams)
            ->withCookieParams($cookieParams)
            ->withHeader('User-Agent', 'Test User-Agent');

        $handler = $this->createMock(RequestHandlerInterface::class);

        $handler->expects($this->once())->method('handle')->willReturnCallback(
            static function (ServerRequestInterface $request) use ($queryParams, $cookieParams): ResponseInterface  {
                self::assertEquals($queryParams, $request->getQueryParams());
                self::assertEquals($cookieParams, $request->getCookieParams());
                self::assertEquals('Test User-Agent', $request->getHeader('User-Agent')[0]);
                return (new ResponseFactory())->createResponse();
            }
        );
        $middleware->process($request, $handler);
    }

    public function testEmpty(): void
    {
        $checkSessionHandler = $this->createMock(Handler::class);
        $validator = new Validator(Validation::createValidatorBuilder()
            ->enableAttributeMapping()
            ->getValidator());

        $middleware = new CheckSessionMiddleware($checkSessionHandler, $validator);

        $request = (new ServerRequestFactory())->createServerRequest('GET', '/test');

        $handler = $this->createMock(RequestHandlerInterface::class);

        self::expectException(ValidationException::class);
        $middleware->process($request, $handler);
    }
}