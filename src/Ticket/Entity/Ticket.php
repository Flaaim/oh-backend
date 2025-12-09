<?php

namespace App\Ticket\Entity;

use App\Shared\Domain\ValueObject\Id;
use App\Shared\Domain\ValueObject\UpdatedAt;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use DomainException;

#[ORM\Entity]
#[ORM\Table(name: 'tickets')]
class Ticket
{
    #[ORM\Id]
    #[ORM\Column(type: 'id', unique: true)]
    private Id $id;
    #[ORM\OneToMany(targetEntity: Question::class, mappedBy: 'ticket', cascade: ['persist'], orphanRemoval: true)]
    private Collection $questions;
    #[ORM\Column(type: 'string', length: 255)]
    private string $cipher;
    #[ORM\Column(type: 'string', length: 255)]
    private string $name;
    #[ORM\Column(type: 'ticket_status', length: 255)]
    private Status $status;
    #[ORM\Column(type: 'datetime_immutable')]
    private DateTimeImmutable $updatedAt;
    #[ORM\OneToOne(targetEntity: Course::class, inversedBy: 'ticket')]
    #[ORM\JoinColumn(name: 'course_id', referencedColumnName: 'id')]
    private ?Course $course = null;
    private function __construct(Id $id, string $cipher, string $name, UpdatedAt $updatedAt)
    {
        $this->id = $id;
        $this->cipher = $cipher;
        $this->name = $name;
        $this->status = Status::inactive();
        $this->updatedAt = $updatedAt->getValue();
        $this->questions = new ArrayCollection();
    }
    public function getId(): Id
    {
        return $this->id;
    }
    public function getName(): ?string
    {
        return $this->name;
    }
    public function getCipher(): ?string
    {
        return $this->cipher;
    }
    public function getQuestions(): Collection
    {
        return $this->questions;
    }
    public function getStatus(): Status
    {
        return $this->status;
    }
    public function getUpdatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }
    public static function fromArray(array $data): self
    {
        $ticket = new self(
           new Id($data['id']),
           $data['cipher'],
           $data['name'],
           UpdatedAt::create($data['updatedAt']),
        );

        if(!empty($data['questions'])){
            foreach ($data['questions'] as $questionData){
                $question = Question::fromArray($questionData);
                $ticket->addQuestions($question);
            }
        }

        return $ticket;
    }
    public function setStatus(Status $newStatus): void
    {
        if($this->status->getValue() !== $newStatus->getValue()){
            $this->status = $newStatus;
        }
    }
    public function setActive(): void
    {
        if($this->status->getValue() === Status::active()->getValue()){
            throw new DomainException('Cannot activate ticket with active status');
        }
        $this->status = Status::active();
    }
    public function addQuestions(Question $question): self
    {
        $this->questions->add($question);
        $question->setTicket($this);
        return $this;
    }
    public function updateQuestionImagesUrl(string $questionId, string $newUrl): void
    {
        foreach ($this->questions->toArray() as $question){
            /** @var Question $question */
            if($question->getId() === $questionId){
                /** @var Question $question */
                $question->setQuestionMainImg($newUrl);
                return;
            }
        }
        throw new \RuntimeException("Question with ID $questionId not found");
    }
    public function updateAnswerImagesUrl(string $answerId, string $newUrl): void
    {
        /** @var Question $question */
        foreach ($this->questions->toArray() as $question){
            /** @var Answer $answer */
            foreach ($question->getAnswers()->toArray() as $answer){
                if($answer->getId()->getValue() === $answerId){
                    $answer->setAnswerImg($newUrl);
                    return;
                }
            }
        }
        throw new \RuntimeException("Answer with ID $answerId not found");
    }
    public function updateFrom(self $newTicket): self
    {
        $this->name = $newTicket->getName();
        $this->cipher = $newTicket->getCipher();
        $this->status = $newTicket->getStatus();

        return $this;
    }
    public function getCourse(): ?Course
    {
        return $this->course;
    }
    public function setCourse(?Course $course): self
    {
        if($course === null && $this->course !== null){
            $this->course->setTicket(null);
        }

        if($course !== null && $course->getTicket() !== $this){
            $course->setTicket($this);
        }

        $this->course = $course;
        return $this;
    }
}
