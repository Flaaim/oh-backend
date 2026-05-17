<?php

declare(strict_types=1);

namespace App\Payment\Entity;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

final class PaymentRepository
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
    public function getByExternalId(string $paymentId): Payment
    {
        if (!$payment = $this->repo->findOneBy(['externalId' => $paymentId])) {
            throw new \DomainException('Payment not found.');
        }
        return $payment;
    }

    public function update(Payment $payment): void
    {
        $this->em->persist($payment);
    }
    public function getByToken(string $returnToken): Payment
    {
        $queryBuilder = $this->repo->createQueryBuilder('p');
        $payment = $queryBuilder->where('p.returnToken.value = :token')
            ->setParameter('token', $returnToken)
            ->getQuery()
            ->getOneOrNullResult();

        if (!$payment) {
            throw new \DomainException('Payment not found.');
        }

        return $payment;
    }

    public function hasDiscount(Email $email): bool
    {
        $queryBuilder = $this->repo->createQueryBuilder('p')
            ->select('COUNT(p.email)')
            ->where('p.email = :email')
            ->setParameter('email', $email->getValue())
            ->getQuery()
            ->getSingleScalarResult();

        return $queryBuilder > 0;
    }
}
