final <?php

namespace App\Access\Entity;


use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class AccessRepository
{
    private EntityRepository $repo;
    private EntityManagerInterface $em;



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

        if(!$access){
            throw new \DomainException('Access not found. token: '. $token);
        }

        return $access;
    }
}