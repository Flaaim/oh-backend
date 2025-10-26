<?php

namespace Test\Functional\Product\Upsert;

use App\Product\Entity\Currency;
use App\Product\Entity\File;
use App\Product\Entity\Price;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Persistence\ObjectManager;
use Test\Functional\Payment\ProductBuilder;

class RequestFixture extends AbstractFixture
{
    public function load(ObjectManager $manager): void
    {
        $product = (new ProductBuilder())
            ->withName('ПИ 1791.10 Итоговое тестирование по Программе IП')
            ->withCipher('ПИ 1791.10')
            ->withPrice(new Price(550.00, new Currency('RUB')))
            ->withFile(new File('fire/1791/pi1791.10.docx'))
            ->withCourse('1791')
            ->build();

        $manager->persist($product);

        $manager->flush();
    }
}