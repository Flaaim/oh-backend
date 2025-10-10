<?php

namespace App\Payment\Entity;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class PaymentRepository
{
    private EntityRepository $repo;
    private EntityManagerInterface $em;
    public function __construct(EntityManagerInterface $em)
    {
        $repo = $em->getRepository(Payment::class);
        $this->repo = $repo;
        $this->em = $em;

    }
    public function create(Payment $payment): void
    {
        $this->em->persist($payment);
    }

}