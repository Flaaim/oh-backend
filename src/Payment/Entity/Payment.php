<?php

namespace App\Payment\Entity;

use App\Product\Entity\Price;
use App\Shared\ValueObject\Id;

class Payment
{
    private Id $id;
    private Email $email;
    private string $productId;
    private Price $price;
    private \DateTimeImmutable $createdAt;
    public function __construct(Id $id, Email $email, string $productId, Price $price, \DateTimeImmutable $createdAt)
    {
        $this->id = $id;
        $this->email = $email;
        $this->productId = $productId;
        $this->price = $price;
        $this->createdAt = $createdAt;
    }
    public function getId(): Id
    {
        return $this->id;
    }
    public function getEmail(): Email
    {
        return $this->email;
    }
    public function getProductId(): string
    {
        return $this->productId;
    }
    public function getPrice(): Price
    {
        return $this->price;
    }
    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }
}