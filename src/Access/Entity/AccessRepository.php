<?php

namespace App\Access\Entity;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class AccessRepository
{
    private EntityRepository $repo;
    private EntityManagerInterface $em;
    public function __construct(EntityManagerInterface $em)
    {
        $repo = $em->getRepository(Access::class);
        $this->repo = $repo;
        $this->em = $em;
    }
    public function get(AccessId $id): Access
    {
        if (!$access = $this->repo->find($id)) {
            throw new \DomainException('Access not found');
        }
        return $access;
    }

    public function create(Access $access): void
    {
        $this->em->persist($access);
    }

    public function getByToken(string $token)
    {
        $queryBuilder = $this->repo->createQueryBuilder('p');
        $access = $queryBuilder->where('p.token.value = :token')
            ->setParameter('token', $token)
            ->getQuery()
            ->getOneOrNullResult();

        if (!$access) {
            throw new \DomainException('Access not found. token: ' . $token);
        }

        return $access;
    }
}
