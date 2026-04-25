<?php

namespace Test\Functional\Distribution\Create;

use App\Recipient\Entity\Email;
use App\Recipient\Entity\Recipient;
use App\Recipient\Entity\RecipientId;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Persistence\ObjectManager;

class RequestFixture extends AbstractFixture
{

    public function load(ObjectManager $manager): void
    {
        $recipient = new Recipient(
            RecipientId::generate(),
            new Email('test1@app.ru'),
        );

        $manager->persist($recipient);

        $manager->flush();
    }
}