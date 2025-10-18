<?php

namespace Test\Functional;

use App\Http\Action\CreatePayment;
use App\Http\Action\HookPayment;
use App\Http\JsonResponse;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Psr7\Factory\ServerRequestFactory;

class WebTestCase extends TestCase
{
    private ?MailerClient $mailer = null;
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
            case '/payment-service/process-payment':
                $action = $container->get(CreatePayment\RequestAction::class);
                /** @var CreatePayment\RequestAction $action */
                $response = $action->handle($request);
                break;
            case '/payment-service/payment-webhook':
                $action = $container->get(HookPayment\RequestAction::class);
                /** @var HookPayment\RequestAction $action */
                $response = $action->handle($request);
                break;
            default:
                $response = new JsonResponse(['message' => 'Not found'], 404);
        }
        return $response;
    }

    protected function mailer(): MailerClient
    {
        if (null === $this->mailer) {
            $this->mailer = new MailerClient();
        }
        return $this->mailer;
    }
}