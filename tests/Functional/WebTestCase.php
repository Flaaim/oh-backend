<?php

namespace Test\Functional;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\App;
use Slim\Psr7\Factory\ServerRequestFactory;
use Telegram\Bot\Api;
use Test\Functional\Telegram\TelegramClient;

class WebTestCase extends TestCase
{
    private ?MailerClient $mailer = null;
    protected ?TelegramClient $telegram = null;
    protected static function json(string $method, string $path, array $body = []): ServerRequestInterface
    {
        $request = self::request($method, $path)
            ->withHeader('Accept', 'application/json')
            ->withHeader('Content-Type', 'application/json')
            ->withHeader('Authorization', 'Bearer YXBwOnNlY3JldA==');
        $request->getBody()->write(json_encode($body, JSON_THROW_ON_ERROR));
        return $request;
    }
    protected static function formData(string $method, string $path, array $body = [], array $file = []): ServerRequestInterface
    {
        $request = self::request($method, $path)
            ->withHeader('Accept', 'multipart/form-data')
            ->withHeader('Content-Type', 'multipart/form-data')
            ->withHeader('Authorization', 'Bearer YXBwOnNlY3JldA==');

        $request = $request->withParsedBody($body);
        $request = $request->withUploadedFiles($file);
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
    protected function app(): App
    {
        /** @var App */
        return (require __DIR__ . '/../../config/app.php')($this->container());
    }
    protected function mailer(): MailerClient
    {
        if (null === $this->mailer) {
            $this->mailer = new MailerClient();
        }
        return $this->mailer;
    }
    public function telegram(): TelegramClient
    {
        if (null === $this->telegram) {
            $this->telegram = new TelegramClient($this->container()->get(Api::class));
        }
        return $this->telegram;
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