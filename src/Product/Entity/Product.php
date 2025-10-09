<?php

namespace App\Product\Entity;

use App\Shared\ValueObject\Id;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'products')]
class Product
{
    #[ORM\Id]
    #[ORM\Column(type: 'id', unique: true)]
    private Id $id;
    #[ORM\Column(type: 'string', length: 255)]
    private string $name;
    #[ORM\Column(type: 'price')]
    private Price $price;
    #[ORM\Column(type: 'string', length: 255)]
    private string $path;
    #[ORM\Column(type: 'string', length: 10)]
    private string $version;
    public function __construct(Id $id, string $name, Price $price, string $path, string $version)
    {
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
        $this->path = $path;
        $this->version = $version;
    }
    public function getId(): Id
    {
        return $this->id;
    }
    public function getName(): string
    {
        return $this->name;
    }
    public function getPrice(): Price
    {
        return $this->price;
    }
    public function getPath(): string
    {
        return $this->path;
    }
    public function getVersion(): string
    {
        return $this->version;
    }
}