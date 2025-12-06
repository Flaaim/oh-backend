<?php

namespace App\Ticket\Test\Builder;

use App\Ticket\Entity\Status;
use App\Ticket\Entity\Ticket;
use App\Shared\Domain\ValueObject\Id;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class TicketBuilder
{
    private  Id $id;
    private  string $name;
    private  string $cipher;
    private  Status $status;
    private Collection $questions;
    private readonly ?DateTimeImmutable $updatedAt;

    public function __construct(
        Id $id,
        string $name = null,
        string $cipher = null,
        DateTimeImmutable $updatedAt = null)
    {
        $this->id = $id;
        $this->cipher = $cipher;
        $this->name = $name;
        $this->status = Status::inactive();
        $this->updatedAt = $updatedAt;
        $this->questions = new ArrayCollection();
    }

    public function build(): Ticket
    {
        $ticket = new Ticket(
            $this->id,
            $this->cipher,
            $this->name,
            new DateTimeImmutable(),
        );

        if($this->status === Status::active()) {
            $ticket->setActive();
        }
        return $ticket;
    }
    public function active(): self
    {
        $this->status = Status::active();
        return $this;
    }
    public function withQuestions(Collection $questions): self
    {
        $this->questions = $questions;
        return $this;
    }

}
