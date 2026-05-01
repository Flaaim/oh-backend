<?php

namespace App\Distribution\Entity;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class DistributionRepository
{
    private EntityRepository $repo;
    private EntityManagerInterface $em;
    public function __construct(EntityManagerInterface $em)
    {
        $repo = $em->getRepository(Distribution::class);
        $this->repo = $repo;
        $this->em = $em;
    }
    public function add(Distribution $distribution): void
    {
        $this->em->persist($distribution);
    }

    public function findById(DistributionId $id): ?Distribution
    {
        return $this->repo->findOneBy(['id' => $id]);
    }

    public function findAll(): array
    {
        return $this->repo->findAll();
    }
}
