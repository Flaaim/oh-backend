<?php

namespace App\Shared\Infrastructure\Persistence;

use App\Recipient\Entity\Recipient;
use App\Shared\Domain\RecipientQuery\RecipientFilter;
use App\Shared\Domain\RecipientQuery\RecipientQueryInterface;
use Doctrine\ORM\EntityManagerInterface;

class RecipientQuery implements RecipientQueryInterface
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    public function getIterable(?RecipientFilter $filter = null): iterable
    {
        $qb = $this->em->createQueryBuilder()
        ->select('r.email')
        ->from(Recipient::class, 'r')
        ->where('r.isActive = :active')
        ->setParameter('active', true);

        if ($filter !== null && $filter->categories !== null) {
            $qb->andWhere('r.category IN (:categories)')
                ->setParameter('categories', $filter->categories);
        }

        return $qb->getQuery()->toIterable();
    }
}
