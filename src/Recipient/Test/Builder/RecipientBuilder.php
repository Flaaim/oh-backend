<?php

declare(strict_types=1);

namespace App\Recipient\Test\Builder;

use App\Recipient\Entity\Email;
use App\Recipient\Entity\Recipient;
use App\Recipient\Entity\RecipientId;

final class RecipientBuilder
{
    private RecipientId $id;
    private Email $email;
    private bool $isActive;
    private \DateTimeImmutable $createdAt;

    public function __construct()
    {
        $this->id = new RecipientId('fbe7e106-cd58-4076-be9d-035cbd71e625');
        $this->email = new Email('recipient@app.ru');
        $this->isActive = true;
        $this->createdAt = new \DateTimeImmutable('now');
    }

    public function withId(RecipientId $id): self
    {
        $this->id = $id;
        return $this;
    }
    public function withEmail(Email $email): self
    {
        $this->email = $email;
        return $this;
    }
    public function build(): Recipient
    {
        return new Recipient(
            $this->id,
            $this->email,
            $this->isActive,
        );
    }
}
