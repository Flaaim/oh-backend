<?php

namespace App\Recipient\Entity;

interface RecipientRepository
{
    public function findByEmail(Email $email): ?Recipient;

    public function add(Recipient $recipient): void;
}