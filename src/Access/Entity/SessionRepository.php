<?php

declare(strict_types=1);

namespace App\Access\Entity;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

final class SessionRepository
{
    private EntityRepository $repo;
    private EntityManagerInterface $em;
    public function __construct(EntityManagerInterface $em)
    {
        $repo = $em->getRepository(Session::class);
        $this->repo = $repo;
        $this->em = $em;
    }
    public function findByToken(string $token): ?Session
    {
        return $this->repo->findOneBy(['token' => $token]);
    }

    public function create(Session $session): void
    {
        $this->em->persist($session);
    }
}
