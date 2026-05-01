<?php

namespace App\Recipient\Entity;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class RecipientRepository
{
    private EntityRepository $repo;
    private EntityManagerInterface $em;
    public function __construct(EntityManagerInterface $em)
    {
        $repo = $em->getRepository(Recipient::class);
        $this->repo = $repo;
        $this->em = $em;
    }
    public function findByEmail(Email $email): ?Recipient
    {
        return $this->repo->findOneBy(['email' => $email]);
    }
    public function findAll(): array
    {
        return $this->repo->findAll();
    }
    public function add(Recipient $recipient): void
    {
        $this->em->persist($recipient);
    }
    public function findAllByEmails(array $emails): array
    {
        $qb = $this->repo->createQueryBuilder('r');
        $qb->where($qb->expr()->in('r.email', ':emails'))
            ->setParameter('emails', $emails);

        return $qb->getQuery()->getResult();
    }
}
