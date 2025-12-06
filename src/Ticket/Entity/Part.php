<?php

namespace App\Ticket\Entity;

use App\Shared\Domain\ValueObject\Id;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'parts')]
class Part
{
    #[ORM\Id]
    #[ORM\Column(type: 'id', unique: true)]
    private Id $id;
    #[ORM\Column(type: 'string', length: 255)]
    private string $name;
    #[ORM\Column(type: 'string', length: 255)]
    private string $description;
    #[ORM\OneToOne(targetEntity: Course::class, mappedBy: 'part')]
    private ?Course $course = null;

    public function __construct(Id $id, String $name, String $description)
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
    public function getCourse(): ?Course
    {
        return $this->course;
    }
    public function setCourse(?Course $course): self
    {
        $this->course = $course;
        return $this;
    }
}
