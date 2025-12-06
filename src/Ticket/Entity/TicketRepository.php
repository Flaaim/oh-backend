<?php

namespace App\Ticket\Entity;

use App\Shared\Domain\ValueObject\Id;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class TicketRepository
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly EntityRepository $repo
    )
    {}
    public function addOrUpdate(Ticket $ticket): void
    {
        $existing = $this->findExisting($ticket);
        if($existing) {
            $existing->updateFrom($ticket);
        }else{
            $this->em->persist($ticket);
        }
    }
    public function getById(Id $id): Ticket
    {
        $ticket = $this->repo->find($id);
        if(!$ticket) {
            throw new \DomainException('Ticket not found.');
        }
        return $ticket;
    }
    public function remove(Ticket $ticket): void
    {
        $this->em->remove($ticket);
    }
    private function findExisting(Ticket $ticket): ?Ticket
    {
        return $this->repo->find($ticket->getId()->getValue());
    }
}
