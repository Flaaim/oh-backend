<?php

namespace App\Shared\Domain\Response;

use App\Ticket\Entity\Ticket;
use Symfony\Component\Yaml\Yaml;

class TicketResponse implements \JsonSerializable
{
    public function __construct(
        public readonly string $id,
        public readonly ?string $name,
        public readonly ?string $cipher,
        public readonly string $status,
        public readonly array $questions,
    ) {
    }

    public static function fromResult(Ticket $ticket, $limit = null): self
    {

        return new self(
            $ticket->getId(),
            $ticket->getName(),
            $ticket->getCipher(),
            $ticket->getStatus()->getValue(),
            $ticket->getQuestions()->slice(0, $limit),
        );
    }
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'cipher' => $this->cipher,
            'status' => $this->status,
            'questions' => array_map(
                fn ($question) => [
                    'id' => $question->getId(),
                    'number' => $question->getNumber(),
                    'text' => $question->getText(),
                    'image' => $question->getQuestionMainImg(),
                    'answers' => array_map(
                        fn ($answer) => [
                            'id' => $answer->getId()->getValue(),
                            'text' => $answer->getText(),
                            'isCorrect' => $answer->isCorrect(),
                            'image' => $answer->getImg(),
                        ],
                        $question->getAnswers()->toArray()
                    )
                ],
                $this->questions
            )
        ];
    }

    public function yamlSerialize(): string
    {
        $data = ['questions' => []];

        foreach ($this->questions as $question) {
            $questionData = [
                'name' => "{$question->getNumber()}. {$question->getText()}",
                'answers' => []
            ];

            foreach ($question->getAnswers() as $answer) {
                $questionData['answers'][] = [
                    'name' => $answer->getText(),
                    'right' => $answer->isCorrect() ? 1 : 0
                ];
            }

            $data['questions'][] = $questionData;
        }

        return Yaml::dump($data, 3, 2);
    }
}
