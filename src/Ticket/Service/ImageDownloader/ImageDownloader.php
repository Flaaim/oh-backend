<?php

namespace App\Ticket\Service\ImageDownloader;

use App\Ticket\Entity\Answer;
use App\Ticket\Entity\Question;
use App\Ticket\Entity\Ticket;
use GuzzleHttp\Client;

class ImageDownloader
{
    public function __construct(
        private readonly PathManager    $manager,
        private readonly Client         $client,
        private readonly Ticket         $ticket,
        private readonly DownloadChecker $checker
    )
    {
        $this->manager
            ->forTicket($ticket->getId()->getValue())
                ->create();
    }
    public function download(): array
    {
        $results = [];
        foreach ($this->ticket->getQuestions() as $question) {
                /** @var Question $question */
                if (!$this->shouldDownloadQuestionImage($question)) {
                    continue;
                }

                $this->manager
                    ->forQuestion($question->getId())
                        ->create();

                $questionImagePath = $this->manager->getImagePath($question->getQuestionMainImg());
                $results['questions'][] = $this->downloadQuestionImage($question, $questionImagePath);
                foreach ($question->getAnswers() as $answer) {
                    /** @var Answer $answer */
                    if(!$this->shouldDownloadAnswerImage($answer)) {
                        continue;
                    }

                    $this->manager->forAnswer($answer->getId()->getValue())
                        ->create();

                    $answerImagePath = $this->manager->getImagePath($answer->getImg());

                    $results['answers'][] = $this->downloadAnswerImage($answer, $answerImagePath);
                }
                sleep(1);
        }
        return $results;
    }

    private function shouldDownloadQuestionImage(Question $question): bool
    {
        return $this->checker->shouldDownloadQuestionImage($question);
    }
    private function shouldDownloadAnswerImage(Answer $answer): bool
    {
        return $this->checker->shouldDownloadAnswerImage($answer);
    }
    private function downloadQuestionImage(Question|Answer $question, string $imagePath): array
    {
        try {
            $this->client->get($question->getQuestionMainImg(), ['sink' => $imagePath,
                'headers' => [
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
                ],
                'timeout' => 30,
                'connect_timeout' => 10
            ]);

            return [
                'question_id' => $question->getId(),
                'url' => $question->getQuestionMainImg(),
                'status' => 'success',
                'path' => $imagePath,
                'downloaded_at' => new \DateTimeImmutable()
            ];

        } catch (\Exception $e) {
            return [
                'question_id' => $question->getId(),
                'url' => $question->getQuestionMainImg(),
                'status' => 'error',
                'error' => $e->getMessage(),
                'attempted_at' => new \DateTimeImmutable()
            ];
        }
    }
    private function downloadAnswerImage(Answer $answer, string $imagePath): array
    {
        try {
            $this->client->get($answer->getImg(), ['sink' => $imagePath,
                'headers' => [
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
                ],
                'timeout' => 30,
                'connect_timeout' => 10
            ]);

            return [
                'answer_id' => $answer->getId()->getValue(),
                'url' => $answer->getImg(),
                'status' => 'success',
                'path' => $imagePath,
                'downloaded_at' => new \DateTimeImmutable()
            ];

        } catch (\Exception $e) {
            return [
                'answer_id' => $answer->getId(),
                'url' => $answer->getImg(),
                'status' => 'error',
                'error' => $e->getMessage(),
                'attempted_at' => new \DateTimeImmutable()
            ];
        }
    }
}
