<?php

namespace Test\Functional\Payment\Result;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Persistence\ObjectManager;
use Test\Functional\Payment\PaymentBuilder;
use Test\Functional\YookassaClient;

class RequestFixture extends AbstractFixture
{

    public function load(ObjectManager $manager): void
    {
        $paymentId = (new YookassaClient())->getLastPayment();
        if($paymentId === null) {
            throw new \Exception('Payment id is null');
        }

        $payment = (new PaymentBuilder())
            ->withExternalId($paymentId)
            ->withSucceededStatus()
            ->build();

        $manager->persist($payment);

        $manager->flush();
    }
}