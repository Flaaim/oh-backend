<?php

namespace Test\Functional\Ticket\CreateOrUpdate;

use App\Shared\Domain\ValueObject\Id;
use App\Ticket\Test\Builder\QuestionCollectionBuilder;
use App\Ticket\Test\Builder\TicketBuilder;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Persistence\ObjectManager;

class RequestFixture extends AbstractFixture
{

    public function load(ObjectManager $manager): void
    {
        $ticket = (new TicketBuilder())
            ->withId(new Id('8c68fbe7-c32d-4bec-a094-fd5d9773ca35'))
            ->withQuestions(
            (new QuestionCollectionBuilder())->build()
        )->build();

        $manager->persist($ticket);

        $manager->flush();
    }
}