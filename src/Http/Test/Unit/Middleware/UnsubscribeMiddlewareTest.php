<?php

namespace App\Http\Test\Unit\Middleware;


use App\Http\Middleware\UnsubscribeMiddleware;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;
use Slim\Psr7\Factory\ResponseFactory;
use Slim\Psr7\Factory\ServerRequestFactory;
use Test\Functional\Json;

class UnsubscribeMiddlewareTest extends TestCase
{
    private ContainerInterface $container;
    private LoggerInterface $logger;
    private UnsubscribeMiddleware $middleware;
    public function setUp(): void
    {
        parent::setUp();
        $this->container = $this->createMock(ContainerInterface::class);
        $this->logger = $this->createMock(LoggerInterface::class);

        $this->middleware = new UnsubscribeMiddleware($this->container, $this->logger);
    }
    public function testAuthTokenInvalid(): void
    {

        $request = (new ServerRequestFactory())->createServerRequest(
            'POST', '/unsubscribe')
            ->withParsedBody($this->getRequestData());

        $this->container->expects($this->once())
            ->method('get')->willReturn([
                'uniSender' => [
                    'apiKey' => 'invalid'
                ]
            ]);
        $this->logger->expects($this->once())
            ->method('error')->with($this->equalTo('Auth in unsubscribed request invalid.'));

        $handler = $this->createMock(RequestHandlerInterface::class);

        $response = $this->middleware->process($request, $handler);

        self::assertJson($body = (string)$response->getBody());
        $data = Json::decode($body);

        self::assertEquals([
            'error' => 'Forbidden'
        ], $data);
    }
    public function testSuccess(): void
    {
        $request = (new ServerRequestFactory())->createServerRequest(
            'POST', '/unsubscribe')
            ->withParsedBody($this->getRequestData('unsubscribed'));

        $this->container->expects($this->once())
            ->method('get')->willReturn([
                'uniSender' => [
                    'apiKey' => 'test-auth'
                ]
            ]);

        $handler = $this->createMock(RequestHandlerInterface::class);

        $handler->expects($this->once())->method('handle')->willReturnCallback(
            static function(ServerRequestInterface $request): ResponseInterface {
                self::assertEquals(['recipient1@example.com'], $request->getParsedBody());
                return (new ResponseFactory())->createResponse();
            }
        );

        $this->middleware->process($request, $handler);
    }

    public function testSpamBlock(): void
    {
        $request = (new ServerRequestFactory())->createServerRequest(
            'POST', '/unsubscribe')
            ->withParsedBody($this->getRequestSpamBlockData());

        $this->container->expects($this->once())
            ->method('get')->willReturn([
                'uniSender' => [
                    'apiKey' => 'test-auth'
                ]
            ]);

        $this->logger->expects($this->once())->method('critical')
            ->with(
                $this->equalTo('ВНИМАНИЕ! UniSender заблокировал отправку (Spam Block)!'),
                $this->equalTo(['event_data' => [
                    'job_id' => '1a3Q2V-0000OZ-S0',
                    'metadata' => [
                        'block_time' => '2015-11-30 15:09:42',
                        'block_type' => 'one_smtp',
                        'domain' => 'domain.com',
                        "SMTP_blocks_count" => 8,
                        "domain_status" => "blocked",
                    ]
                ]])
            );
        $handler = $this->createMock(RequestHandlerInterface::class);

        $handler->expects($this->once())->method('handle')->willReturnCallback(
            static function(ServerRequestInterface $request): ResponseInterface {
                self::assertEquals([], $request->getParsedBody());
                return (new ResponseFactory())->createResponse();
            }
        );

        $this->middleware->process($request, $handler);
    }
    private function getRequestData(string $status = 'sent'): array
    {

        return [
            'auth' => 'test-auth',
            'events_by_user' => [
                [
                    'user_id' => 456,
                    'project_id' => '6432890213745872',
                    'project_name' => 'MyProject',
                    'events' => [
                        [
                            'event_name' => 'transactional_email_status',
                            'event_data' => [
                                'job_id' => '1a3Q2V-0000OZ-S0',
                                'metadata' => [
                                    'key1' => 'val1',
                                    'key2' => 'val2'
                                ],
                                'email' => 'recipient1@example.com',
                                'status' => $status,
                                'event_time' => '2015-11-30 15:09:42',
                                'url' => 'http://some.url.com',
                                'delivery_info' => [
                                    'delivery_status' => 'err_delivery_failed',
                                    'destination_response' => '550 Spam rejected',
                                    'user_agent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/57.0.2987.133 Safari/537.36',
                                    'ip' => '111.111.111.111'
                                ]
                            ]
                        ],
                        [
                            'event_name' => 'transactional_email_status',
                            'event_data' => [
                                'job_id' => '1a3Q2V-0000OZ-S0',
                                'metadata' => [
                                    'key1' => 'val1',
                                    'key2' => 'val2'
                                ],
                                'email' => 'recipient2@example.com',
                                'status' => 'sent',
                                'event_time' => '2015-11-30 15:09:42',
                                'url' => 'http://some.url.com',
                                'delivery_info' => [
                                    'delivery_status' => 'err_delivery_failed',
                                    'destination_response' => '550 Spam rejected',
                                    'user_agent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/57.0.2987.133 Safari/537.36',
                                    'ip' => '111.111.111.111'
                                ]
                            ]
                        ],
                    ]
                ]
            ]
        ];
    }

    private function getRequestSpamBlockData(): array
    {
        return [
            'auth' => 'test-auth',
            'events_by_user' => [
                [
                    'user_id' => 456,
                    'project_id' => '6432890213745872',
                    'project_name' => 'MyProject',
                    'events' => [
                        [
                            'event_name' => 'transactional_spam_block',
                            'event_data' => [
                                'job_id' => '1a3Q2V-0000OZ-S0',
                                'metadata' => [
                                    'block_time' => '2015-11-30 15:09:42',
                                    'block_type' => 'one_smtp',
                                    'domain' => 'domain.com',
                                    "SMTP_blocks_count" => 8,
                                    "domain_status" => "blocked",
                                ]
                            ],
                        ]
                    ]
                ]
            ]
        ];
    }
}