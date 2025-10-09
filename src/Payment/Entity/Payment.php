<?php

namespace App\Payment\Entity;

use App\Product\Entity\Price;
use App\Shared\ValueObject\Id;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'payments')]
class Payment
{
    #[ORM\Id]
    #[ORM\Column(type:'id', length: 255)]
    private Id $id;
    #[ORM\Column(type:'external_id', length: 255, nullable: true)]
    private ?string $externalId = null;
    private Status $status;
    #[ORM\Column(type: 'email')]
    private Email $email;
    #[ORM\Column(type:'product_id', length: 255)]
    private string $productId;
    #[ORM\Column(type:'price')]
    private Price $price;
    #[ORM\Column(type:'datetime_immutable')]
    private \DateTimeImmutable $createdAt;
    public function __construct(Id $id, Email $email, string $productId, Price $price, \DateTimeImmutable $createdAt)
    {
        $this->id = $id;
        $this->status = Status::pending();
        $this->email = $email;
        $this->productId = $productId;
        $this->price = $price;
        $this->createdAt = $createdAt;
    }
    public function getId(): Id
    {
        return $this->id;
    }
    public function getStatus(): Status
    {
        return $this->status;
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
    public function setExternalId(string $externalId): void
    {
        $this->externalId = $externalId;
    }
    public function getExternalId(): string
    {
        return $this->externalId;
    }
    public function setStatus(Status $newStatus): void
    {
        if($this->status->getValue() === $newStatus->getValue()) {
            throw new \DomainException('Status already set');
        }
        $this->status = $newStatus;
    }
}