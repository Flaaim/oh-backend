<?php

namespace App\Product\Fixture;

use App\Product\Entity\Currency;
use App\Product\Entity\Price;
use App\Product\Entity\Product;
use App\Shared\ValueObject\Id;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Persistence\ObjectManager;

class ProductFixture extends AbstractFixture
{

    public function load(ObjectManager $manager): void
    {
        $product = new Product(
            Id::generate(),
            'СИЗ образцы документов',
            new Price(450.00, new Currency()),
            sys_get_temp_dir(),
            '1'
        );

        $manager->persist($product);

        $manager->flush();
    }
}