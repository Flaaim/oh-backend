<?php

namespace App\Product\Entity;

use App\Shared\ValueObject\Id;

class Product
{
    private Id $id;
    private string $name;
    private float $price;
    private string $path;
    private string $version;
    public function __construct(Id $id, string $name, string $price, string $path, string $version)
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
    public function getPrice(): float
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