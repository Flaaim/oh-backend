<?php

namespace App\Access\Test\Builder;

use App\Access\Entity\Access;
use App\Access\Entity\AccessId;
use App\Access\Entity\Email;
use App\Access\Entity\Token;

class AccessBuilder
{
    private AccessId $accessId;
    private string $name;
    private string $cipher;
    private Email $email;
    private string $productId;
    private Token $token;

    public function __construct()
    {
        $this->accessId = new AccessId('47fba766-2362-4f8e-8dd0-6b1bc948e11a');
        $this->name = 'Оказание первой помощи пострадавшим';
        $this->cipher = 'ОТ 201.18';
        $this->email = new Email('test@email.ru');
        $this->productId = 'b38e76c0-ac23-4c48-85fd-975f32c8801f';
        $this->token =  new Token('14415f3b-3f03-48fc-8abb-435353da4ac5', new \DateTimeImmutable('+3 days'));
    }
    public function withAccessId(AccessId $accessId): self
    {
        $this->accessId = $accessId;
        return $this;
    }
    public function withName(string $name): self
    {
        $this->name = $name;
        return $this;
    }
    public function withCipher(string $cipher): self
    {
        $this->cipher = $cipher;
        return $this;
    }
    public function withEmail(Email $email): self
    {
        $this->email = $email;
        return $this;
    }
    public function withToken(Token $token): self
    {
        $this->token = $token;
        return $this;
    }
    public function withProductId(string $productId): self
    {
        $this->productId = $productId;
        return $this;
    }
    public function build(): Access
    {
        return  new Access(
            $this->accessId,
            $this->name,
            $this->cipher,
            $this->email,
            $this->productId,
            $this->token,
        );

    }
}