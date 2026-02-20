<?php

namespace App\Access\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'accesses')]
class Access
{
    public function __construct(
        #[ORM\Id]
        #[ORM\Column(type:'id', length: 255)]
        private AccessId $id,
        #[ORM\Column(type:'string', length: 255)]
        private string $name,
        #[ORM\Column(type:'string', length: 25)]
        private string $cipher,
        #[ORM\Column(type: 'email')]
        private Email $email,
        #[ORM\Column(type:'string', length: 255)]
        private string $productId,
        #[ORM\Embedded(class: Token::class)]
        private Token $token
    ){
    }
    public function getId(): AccessId
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
    public function getToken(): Token
    {
        return $this->token;
    }
    public function isExpired(): bool
    {
        return $this->token->isExpiredTo(new \DateTimeImmutable('now'));
    }
    public function getName(): string
    {
        return $this->name;
    }

    public function getCipher(): string
    {
        return $this->cipher;
    }
}