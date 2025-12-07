<?php

namespace App\Ticket\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'questions')]
final class Question
{
    #[ORM\Id]
    #[ORM\Column(type: 'string', length: 36, unique: true)]
    private string $id;
    #[ORM\Column(type: 'string', length: 255)]
    private string $number;
    #[ORM\Column(type: 'string', length: 1000)]
    private string $text;
    #[ORM\Column(type: 'string', length: 255)]
    private string $questionMainImg;
    #[ORM\OneToMany(targetEntity: Answer::class, mappedBy: 'question', cascade: ['persist'], orphanRemoval: true)]
    private Collection $answers;
    #[ORM\ManyToOne(targetEntity: Ticket::class, inversedBy: 'questions')]
    #[ORM\JoinColumn(name: 'ticket_id', referencedColumnName: 'id', nullable: false)]
    private Ticket $ticket;

    private function __construct(string $id, string $number, string $text, string $questionMainImg)
    {
        $this->id = $id;
        $this->number = $number;
        $this->text = $text;
        $this->questionMainImg = $questionMainImg;
        $this->answers = new ArrayCollection();
    }
    public function getId(): string
    {
        return $this->id;
    }
    public function getNumber(): string
    {
        return $this->number;
    }
    public function getText(): string
    {
        return $this->text;
    }
    public function getQuestionMainImg(): string
    {
        return $this->questionMainImg;
    }
    public function getAnswers(): Collection
    {
        return $this->answers;
    }
    public static function fromArray(array $data): self
    {
        $question =  new self(
            $data['id'],
            $data['number'],
            $data['text'],
            $data['image'],
        );
        if(!empty($data['answers'])){
            foreach ($data['answers'] as $answerData){
                $answer = Answer::fromArray($answerData);
                $question->addAnswers($answer);
            }
        }
        return $question;
    }
    public function setTicket(Ticket $ticket): self
    {
        $this->ticket = $ticket;
        return $this;
    }
    public function addAnswers(Answer $answer): self
    {
        $this->answers->add($answer);
        $answer->setQuestion($this);
        return $this;
    }
    public function setQuestionMainImg(string $questionMainImg): void
    {
        if($this->getQuestionMainImg() !== $questionMainImg){
            $this->questionMainImg = $questionMainImg;
        }
    }
}
