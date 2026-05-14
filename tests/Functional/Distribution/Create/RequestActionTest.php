<?php

declare(strict_types=1);

namespace Test\Functional\Distribution\Create;

use App\Distribution\Entity\Distribution;
use App\Distribution\Entity\DistributionRepository;
use App\Recipient\Entity\Email;
use App\Shared\Domain\Service\Distribution\DistributionInterface;
use App\Shared\Infrastructure\Distribution\UniSender;
use Test\Functional\Distribution\MemoryUniSender;
use Test\Functional\Json;
use Test\Functional\WebTestCase;

class RequestActionTest extends WebTestCase
{
    private DistributionRepository $distributions;
    private MemoryUniSender $uniSenderSpy;
    public function setUp(): void
    {
        parent::setUp();
        $this->loadFixtures([RequestFixture::class]);
        $this->distributions = $this->container->get(DistributionRepository::class);

        $this->uniSenderSpy = $this->container->get(DistributionInterface::class);
        $this->uniSenderSpy->clear();
    }
    public function testSuccess(): void
    {
        $response = $this->app()->handle(self::json('POST', '/payment-service/distribution', [
            'subject' => 'Тестовая отправка рассылки',
            'templateId' => '30b1812e-3f6e-11f1-90b8-76fb82c9d6b5',
        ]));

        self::assertEquals(204, $response->getStatusCode());

        $distributions = $this->distributions->findAll();

        self::assertCount(1, $distributions);
        /** @var array<Distribution> $distributions */
        self::assertEquals('Тестовая отправка рассылки', $distributions[0]->getSubject());

        self::assertTrue($distributions[0]->isEnded());
        $sentMessages = $this->uniSenderSpy->getSentMessages();

        self::assertCount(1, $sentMessages);

        $message = $sentMessages[0];
        self::assertEquals('Тестовая отправка рассылки', $message['subject']);
        self::assertEquals('30b1812e-3f6e-11f1-90b8-76fb82c9d6b5', $message['templateId']);
        self::assertEquals([['email' => (new Email('test1@app.ru'))->getValue()]], $message['recipients']);
    }

    public function testEmpty(): void
    {
        $response = $this->app()->handle(self::json('POST', '/payment-service/distribution'));

        self::assertEquals(422, $response->getStatusCode());

        self::assertJson($body = (string)$response->getBody());

        $data = Json::decode($body);

        self::assertEquals([
            'errors' => [
                'subject' => 'This value should not be blank.',
                'templateId' => 'This value should not be blank.',
            ],
        ], $data);

        $distributions = $this->distributions->findAll();

        self::assertEmpty($distributions);
    }

    public function testFailed(): void
    {
        $this->uniSenderSpy->shouldFail();

        $response = $this->app()->handle(self::json('POST', '/payment-service/distribution', [
            'subject' => 'Тестовая отправка рассылки',
            'templateId' => '30b1812e-3f6e-11f1-90b8-76fb82c9d6b5',
        ]));

        self::assertEquals(500, $response->getStatusCode());

        $distributions = $this->distributions->findAll();
        self::assertCount(1, $distributions);

        self::assertFalse($distributions[0]->isEnded());
    }
}
