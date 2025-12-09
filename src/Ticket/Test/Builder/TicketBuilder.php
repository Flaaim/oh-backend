<?php

namespace App\Ticket\Test\Builder;

use App\Shared\Domain\ValueObject\Id;
use App\Shared\Domain\ValueObject\UpdatedAt;
use App\Ticket\Entity\Question;
use App\Ticket\Entity\Status;
use App\Ticket\Entity\Ticket;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class TicketBuilder
{
    private  Id $id;
    private  string $name;
    private  string $cipher;
    private  Status $status;
    private Collection $questions;
    private string $updatedAt;

    public function __construct()
    {
        $this->id = new Id('8bac1e13-cef0-405b-8fcd-b05c4d394730');
        $this->status = Status::inactive();
        $this->cipher = 'ПБП 115.26';
        $this->name = 'Основы промышленной безопасности';
        $this->updatedAt = '12.11.2025';
        $this->questions = new ArrayCollection();
    }
    public function withId(Id $id): self
    {
        $this->id = $id;
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
    public function withQuestions(Collection $questions): self
    {
        $this->questions = $questions;
        return $this;
    }
    public function withUpdatedAt(string $updatedAt): self
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }
    public function active(): self
    {
        $this->status = Status::active();
        return $this;
    }
    public function build(): Ticket
    {
        $ticket = Ticket::fromArray([
            'id' => $this->id,
            'name' => $this->name,
            'cipher' => $this->cipher,
            'updatedAt' => $this->updatedAt,
        ]);

        if(!$this->questions->isEmpty()) {
            foreach ($this->questions as $questionData) {
                $question = Question::fromArray($questionData);
                $ticket->addQuestions($question);
            }
        }

        if($this->status === Status::active()) {
            $ticket->setActive();
        }

        return $ticket;
    }

}
