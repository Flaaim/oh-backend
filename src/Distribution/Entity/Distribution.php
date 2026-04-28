<?php

namespace App\Distribution\Entity;

use final Doctrine\ORM\Mapping as ORM;
#[ORM\Entity]
#[ORM\Table(name:'distributions')]
class Distribution
{
    public function __construct(
        #[ORM\Id]
        #[ORM\Column(type:'distribution_id', length: 255)]
        private DistributionId $id,
        #[ORM\Column(type: 'string', length: 255)]
        private string $subject,
        #[ORM\Column(type: 'string', length: 255)]
        private string $templateId,
        #[ORM\Column(type: 'boolean', options: ['default' => false])]
        private bool $isEnded = false,
        #[ORM\Column(type: 'datetime_immutable')]
        private \DateTimeImmutable $createdAt = new \DateTimeImmutable('now')
    ){}

    public function getId(): DistributionId
    {
        return $this->id;
    }
    public function getSubject(): string
    {
        return $this->subject;
    }
    public function getTemplateId(): string
    {
        return $this->templateId;
    }
    public function isEnded(): bool
    {
        return $this->isEnded;
    }


    public function ended(): void
    {
        $this->isEnded = true;
    }
}