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
}