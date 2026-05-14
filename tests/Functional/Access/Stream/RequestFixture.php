<?php

declare(strict_types=1);

namespace Test\Functional\Access\Stream;

use App\Access\Entity\AccessId;
use App\Access\Entity\Token;
use App\Access\Test\Builder\AccessBuilder;
use App\Product\Entity\File;
use App\Shared\Domain\ValueObject\Id;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Persistence\ObjectManager;
use Test\Functional\Payment\ProductBuilder;

final class RequestFixture extends AbstractFixture
{
    public function load(ObjectManager $manager): void
    {
        $access = (new AccessBuilder())->withAccessId(AccessId::generate())
            ->withToken(
                new Token('b035e3dc-cadc-45dd-85a1-817b6060d6fe', new \DateTimeImmutable('+3 days')),
            )
            ->build();

        $manager->persist($access);

        $accessExpired = (new AccessBuilder())->withAccessId(AccessId::generate())
            ->withToken(
                new Token('02065614-eb7b-49a9-852d-0490972d4891', new \DateTimeImmutable('-3 days')),
            )->build();

        $manager->persist($accessExpired);

        $product = (new ProductBuilder())->withFile(new File($this->tempFile()))
            ->withId(new Id('b38e76c0-ac23-4c48-85fd-975f32c8801f'))->build();


        $manager->persist($product);

        $manager->flush();
    }
    public function tempFile(): string
    {
        $result = file_put_contents($file1 = sys_get_temp_dir(). '/test-file', '%PDF-1.4 test content');
        if (!$result) {
            throw new \RuntimeException('Failed to write temp file');
        }
        return basename($file1);
    }

}
