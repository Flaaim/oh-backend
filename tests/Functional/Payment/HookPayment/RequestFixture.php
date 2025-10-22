<?php

namespace Test\Functional\Payment\HookPayment;

use App\Payment\Entity\Email;
use App\Payment\Entity\Payment;
use App\Payment\Entity\Token;
use App\Product\Entity\Currency;
use App\Product\Entity\File;
use App\Product\Entity\Price;
use App\Product\Entity\Product;
use App\Shared\ValueObject\Id;
use DateTimeImmutable;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;

class RequestFixture extends AbstractFixture
{

    public function load(ObjectManager $manager): void
    {
        $product = new Product(
            new Id('b38e76c0-ac23-4c48-85fd-975f32c8801f'),
            'Инструктажи образцы документов',
            new Price(450.00, new Currency()),
            new File('/ppe/templates.txt'),
            '1'
        );

        $manager->persist($product);

        $payment = new Payment(
            new Id(Uuid::uuid4()->toString()),
            new Email('test@app.ru'),
            'b38e76c0-ac23-4c48-85fd-975f32c8801f',
            new Price(450.00, new Currency('RUB')),
            new DateTimeImmutable('now'),
            new Token(Uuid::uuid4()->toString(), new DateTimeImmutable('+ 1 hours')),
        );
        $payment->setExternalId('308648ae-000f-5001-8000-127b00f66a5a');

        $manager->persist($payment);

        $manager->flush();
    }
}