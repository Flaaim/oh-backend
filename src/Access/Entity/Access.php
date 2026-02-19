<?php

namespace App\Access\Entity;

class Access
{

    public function __construct(
        private AccessId $id,
        private Email $email,
        private string $productId,
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
}