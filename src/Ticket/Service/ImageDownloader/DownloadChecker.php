<?php

namespace App\Ticket\Service\ImageDownloader;

use App\Ticket\Entity\Answer;
use App\Ticket\Entity\Question;
use App\Ticket\Entity\Ticket;

class DownloadChecker
{
    public function shouldDownload(Ticket $ticket): bool
    {
        $flag = false;
        foreach ($ticket->getQuestions() as $question) {
            /** @var Question $question */
            if($this->shouldDownloadQuestionImage($question)) {
                $flag = true;
            }
        }
        return $flag;
    }
    public function shouldDownloadQuestionImage(Question $question): bool
    {
        if (!empty($question->getQuestionMainImg()) &&
            filter_var($question->getQuestionMainImg(), FILTER_VALIDATE_URL)) {
            return true;
        }

        foreach ($question->getAnswers() as $answer) {
            if ($this->shouldDownloadAnswerImage($answer)) {
                return true;
            }
        }
        return false;
    }

    public function shouldDownloadAnswerImage(Answer $answer): bool
    {
        return !empty($answer->getImg()) &&
            filter_var($answer->getImg(), FILTER_VALIDATE_URL) &&
            $answer->getImg() !== '';
    }
}
