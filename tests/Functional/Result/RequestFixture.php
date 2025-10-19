<?php

namespace Test\Functional\Result;

use App\Payment\Entity\Email;
use App\Payment\Entity\Payment;
use App\Payment\Entity\Status;
use App\Payment\Entity\Token;
use App\Product\Entity\Currency;
use App\Product\Entity\Price;
use App\Shared\ValueObject\Id;
use DateTimeImmutable;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;

class RequestFixture extends AbstractFixture
{

    public function load(ObjectManager $manager): void
    {
        $returnToken = new Token('392b1c38-f3e4-4533-a6cb-5b4e7c08d91f', new DateTimeImmutable('+ 1 hour'));
        $payment = new Payment(
            new Id(Uuid::uuid4()->toString()),
            new Email('test@app.ru'),
            'b38e76c0-ac23-4c48-85fd-975f32c8801f',
            new Price(450.00, new Currency('RUB')),
            new DateTimeImmutable(),
            $returnToken
        );
        $payment->setStatus(Status::succeeded());
        $payment->setSend();
        $payment->setExternalId('308648ae-000f-5001-8000-127b00f66a5a');
        $manager->persist($payment);

        $manager->flush();
    }
}