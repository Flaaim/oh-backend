<?php

namespace App\Ticket\Entity;

use App\Shared\Domain\ValueObject\Id;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'courses')]
class Course
{
    #[ORM\Id]
    #[ORM\Column(type: 'id', unique: true)]
    private Id $id;
    #[ORM\Column(type: 'string', length: 255)]
    private string $name;
    #[ORM\Column(type: 'string', length: 255)]
    private string $description;
    #[ORM\OneToOne(targetEntity: Ticket::class, mappedBy: 'course')]
    private ?Ticket $ticket = null;
    #[ORM\OneToOne(targetEntity: Part::class, inversedBy: 'course')]
    #[ORM\JoinColumn(name: 'part_id', referencedColumnName: 'id')]
    private ?Part $part = null;

    public function __construct(Id $id, string $name, string $description)
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
    }
    public function getId(): Id
    {
        return $this->id;
    }
    public function getName(): string
    {
        return $this->name;
    }
    public function getDescription(): string
    {
        return $this->description;
    }
    public function getTicket(): ?Ticket
    {
        return $this->ticket;
    }
    public function setTicket(?Ticket $ticket): self
    {
        $this->ticket = $ticket;
        return $this;
    }

    public function setPart(?Part $part): self
    {
        if($part === null && $this->part !== null){
            $this->part->setCourse(null);
        }
        if($part !== null && $part->getCourse() !== $this){
            $part->setCourse($this);
        }
        $this->part = $part;
        return $this;
    }

    public function getPart(): ?Part
    {
        return $this->part;
    }
}
