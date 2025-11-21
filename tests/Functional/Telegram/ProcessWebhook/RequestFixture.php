<?php

namespace Test\Functional\Telegram\ProcessWebhook;

use App\Shared\ValueObject\Id;
use App\TelegramBot\Entity\Chat;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;


class RequestFixture extends AbstractFixture
{
    public function load(ObjectManager $manager): void
    {
        $chat = new Chat(
          new Id(Uuid::uuid4()->toString()),
            123456789,
            'TestUser',
            new \DateTimeImmutable()
        );

        $manager->persist($chat);
    }
}