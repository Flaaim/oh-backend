<?php

namespace App\Http\Test\Unit\Middleware;

use App\Http\Middleware\ClearInputHandler;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Factory\ResponseFactory;
use Slim\Psr7\Factory\ServerRequestFactory;


class ClearInputHandlerTest extends TestCase
{
    public function testNormal(): void
    {
        $middleware = new ClearInputHandler();

        $request = (new ServerRequestFactory())->createServerRequest('GET', '/test')->withParsedBody([
            'null' => null,
            'space' => ' ',
            'string' => 'String ',
            'nested' => [
                'null' => null,
                'space' => ' ',
                'name' => ' Name',
            ]
        ]);

        $handler = $this->createMock(RequestHandlerInterface::class);

        $handler->expects($this->once())->method('handle')
            ->willReturnCallback(static function (ServerRequestInterface $request): ResponseInterface {
                self::assertEquals([
                    'null' => null,
                    'space' => '',
                    'string' => 'String',
                    'nested' => [
                        'null' => null,
                        'space' => '',
                        'name' => 'Name',
                    ]
                ], $request->getParsedBody());
                return (new ResponseFactory())->createResponse();
            });

        $middleware->process($request, $handler);

    }
}