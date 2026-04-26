<?php

namespace Test\Functional\Recipient\Deactivate;

use App\Recipient\Entity\Email;
use App\Recipient\Entity\RecipientId;
use App\Recipient\Test\Builder\RecipientBuilder;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Persistence\ObjectManager;

class RequestFixture extends AbstractFixture
{

    public function load(ObjectManager $manager): void
    {
        $recipient1 = (new RecipientBuilder())
            ->withId(new RecipientId('496f2c1d-bb37-49b5-b01e-a7bf5d03b22d'))
            ->withEmail(new Email('sent@app.ru'))
            ->build();

        $recipient2 = (new RecipientBuilder())
            ->withId(new RecipientId('2c8ad882-97fc-4096-a91e-468d0f5b484e'))
            ->withEmail(new Email('unsubscribed@app.ru'))
            ->build();

        $manager->persist($recipient1);
        $manager->persist($recipient2);

        $manager->flush();
    }
}