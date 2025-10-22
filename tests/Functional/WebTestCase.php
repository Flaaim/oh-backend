<?php

namespace Test\Functional;

use App\Http\Action\CreatePayment;
use App\Http\Action\HookPayment;
use App\Http\Action\Result;
use App\Http\JsonResponse;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;
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
                $action = $container->get(\App\Http\Action\Payment\CreatePayment\RequestAction::class);
                /** @var \App\Http\Action\Payment\CreatePayment\RequestAction $action */
                $response = $action->handle($request);
                break;
            case '/payment-service/payment-webhook':
                $action = $container->get(\App\Http\Action\Payment\HookPayment\RequestAction::class);
                /** @var \App\Http\Action\Payment\HookPayment\RequestAction $action */
                $response = $action->handle($request);
                break;
            case '/payment-service/result':
                $action = $container->get(\App\Http\Action\Payment\Result\RequestAction::class);
                /** @var \App\Http\Action\Payment\Result\RequestAction $action */
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

    protected function loadFixtures(array $fixtures): void
    {
        $container = $this->container();
        $loader = new Loader();
        foreach ($fixtures as $name => $class) {
            /** @var AbstractFixture $fixture */
            $fixture = $container->get($class);
            $loader->addFixture($fixture);
        }
        /** @var EntityManagerInterface $em */
        $em = $container->get(EntityManagerInterface::class);
        $executor = new ORMExecutor($em, new ORMPurger($em));
        $executor->execute($loader->getFixtures());
    }
}