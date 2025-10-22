<?php

namespace App\Product\Entity;

use App\Shared\ValueObject\Id;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class ProductRepository
{
    private EntityRepository $repo;
    private EntityManagerInterface $em;
    public function __construct(EntityManagerInterface $em)
    {
        $repo = $em->getRepository(Product::class);
        $this->repo = $repo;
        $this->em = $em;
    }

    public function get(Id $id): Product
    {
        if(!$product = $this->repo->find($id)) {
            throw new \DomainException('Product not found.');
        }
        /** @var Product $product */
        return $product;
    }
    public function findByCipher(string $cipher): ?Product
    {
        return $this->repo->findOneBy(['cipher' => $cipher]);
    }
    public function upsert(Product $product): void
    {
        $qb = $this->em->createQueryBuilder();

        $sql = '
        INSERT INTO products (id, name, price, file, cipher) 
        VALUES (:id, :name, :price, :file, :cipher)
        ON DUPLICATE KEY UPDATE 
            name = VALUES(name),
            price = VALUES(price),
            file = VALUES(file)
         ';

        $qb->getEntityManager()
            ->getConnection()
            ->executeStatement($sql, [
                'id' => $product->getId()->getValue(),
                'name' => $product->getName(),
                'price' => $product->getPrice()->getValue(),
                'file' => $product->getFile()->getPathToFile(),
                'cipher' => $product->getCipher()
            ]);
    }
}