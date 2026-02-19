<?php

namespace App\Access\Entity;

class Access
{
    private AccessId $id;
    private Email $email;

    private string $productId;
    private Token $token;

    private \DateTimeImmutable $createdAt;
}