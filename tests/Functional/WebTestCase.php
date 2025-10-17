<?php

namespace Test\Functional;

use App\Http\Action\CreatePayment\RequestAction;
use App\Http\JsonResponse;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Psr7\Factory\ResponseFactory;
use Slim\Psr7\Factory\ServerRequestFactory;

class WebTestCase extends TestCase
{
    protected static function json(string $method, string $path, array $body = []): ServerRequestInterface
    {
        $request = self::request($method, $path)
            ->withHeader('Accept', 'application/json')
            ->withHeader('Content-Type', 'application/json');
        $request->getBody()->write(json_encode($body, JSON_THROW_ON_ERROR));
        return $request;
    }

    protected static function request(string $method, string $path): ServerRequestInterface
    {
        return (new ServerRequestFactory())->createServerRequest($method, $path);
    }

    private function container(): ContainerInterface
    {
        /** @var ContainerInterface */
        return require __DIR__ . '/../../config/container.php';
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $container = $this->container();
        $path = $request->getUri()->getPath();

        switch($path){
            case '/process-payment':
                $action = $container->get(RequestAction::class);
                /** @var RequestAction $action */
                $response = $action->handle($request);
                break;
            default:
                $response = new JsonResponse(['message' => 'Not found'], 404);
        }
        return $response;
    }
}