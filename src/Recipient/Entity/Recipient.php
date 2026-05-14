<?php

declare(strict_types=1);

namespace App\Recipient\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name:'recipients')]
class Recipient
{
    public function __construct(
        #[ORM\Id]
        #[ORM\Column(type:'recipient_id', length: 255)]
        private RecipientId $id,
        #[ORM\Column(type: 'recipient_email')]
        private Email $email,
        #[ORM\Column(type: 'boolean', options: ['default' => true])]
        private bool $isActive = true,
        #[ORM\Column(type: 'datetime_immutable')]
        private \DateTimeImmutable $createdAt = new \DateTimeImmutable('now')
    ) {
    }

    public function getId(): RecipientId
    {
        return $this->id;
    }
    public function getEmail(): Email
    {
        return $this->email;
    }
    public function isActive(): bool
    {
        return $this->isActive;
    }
    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }
    public function deactivate(): void
    {
        if ($this->isActive) {
            $this->isActive = false;
        }
    }
}
