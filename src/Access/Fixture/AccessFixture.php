final <?php

namespace App\Access\Fixture;

use App\Access\Entity\Access;
use App\Access\Entity\AccessId;
use App\Access\Entity\Email;
use App\Access\Entity\Token;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Persistence\ObjectManager;

class AccessFixture extends AbstractFixture
{

    #[\Override]
    public function load(ObjectManager $manager): void
    {
        $access = new Access(
            new AccessId('47fba766-2362-4f8e-8dd0-6b1bc948e11a'),
            'Оказание первой помощи пострадавшим',
            'ОТ 201.18',
            new Email('test@email.ru'),
            'b38e76c0-ac23-4c48-85fd-975f32c8801f',
            new Token('14415f3b-3f03-48fc-8abb-435353da4ac5', new \DateTimeImmutable('+3 days')),
        );

        $manager->persist($access);
        $manager->flush();
    }
}