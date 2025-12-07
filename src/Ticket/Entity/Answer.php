<?php

namespace App\Ticket\Entity;

use App\Shared\Domain\ValueObject\Id;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'answers')]
final class Answer
{
    #[ORM\Id]
    #[ORM\Column(type: 'id', unique: true)]
    private Id $id;
    #[ORM\Column(type: 'string', length: 500)]
    private string $text;
    #[ORM\Column(type: 'boolean')]
    private bool $isCorrect;
    #[ORM\Column(type: 'string', length: 255)]
    private string $img;
    #[ORM\ManyToOne(targetEntity: Question::class, inversedBy: 'answers')]
    #[ORM\JoinColumn(name: 'question_id', referencedColumnName: 'id', nullable: false)]
    private Question $question;
    private function __construct(Id $id, string $text, bool $isCorrect, string $img)
    {
        $this->id = $id;
        $this->text = $text;
        $this->isCorrect = $isCorrect;
        $this->img = $img;
    }
    public function getId(): Id
    {
        return $this->id;
    }
    public function getText(): string
    {
        return $this->text;
    }
    public function isCorrect(): bool
    {
        return $this->isCorrect;
    }
    public function getImg(): string
    {
        return $this->img;
    }
    public static function fromArray(array $data): self
    {
        return new self(
            new Id($data['id']),
            $data['text'],
            $data['isCorrect'],
            $data['image']
        );
    }
    public function setQuestion(Question $question): self
    {
       $this->question = $question;
       return $this;
    }

    public function setAnswerImg(string $newUrl): void
    {
        if($this->getImg() !== $newUrl){
            $this->img = $newUrl;
        }
    }
}
