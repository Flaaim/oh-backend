<?php

namespace App\Ticket\Test\Unit\Service;

use App\Ticket\Entity\Question;
use App\Ticket\Entity\Ticket;
use App\Ticket\Service\ImageDownloader\DownloadChecker;
use App\Ticket\Test\Builder\QuestionProvider;
use App\Ticket\Test\Builder\TicketProvider;
use PHPUnit\Framework\TestCase;

class DownloadCheckerTest extends TestCase
{
    public function testShouldDownloadQuestionTrue(): void
    {
        $checker = new DownloadChecker();
        $question = Question::fromArray((new QuestionProvider())->toArrayWithImages());

        $this->assertTrue($checker->shouldDownloadQuestionImage($question));

        $question = Question::fromArray((new QuestionProvider())->toArrayWithImagesOnlyInQuestion());
        $this->assertTrue($checker->shouldDownloadQuestionImage($question));

        $question = Question::fromArray((new QuestionProvider())->toArrayWithImagesOnlyInAnswers());
        $this->assertTrue($checker->shouldDownloadQuestionImage($question));
    }


    public function testShouldDownloadQuestionFalse(): void
    {
        $checker = new DownloadChecker();
        $question = Question::fromArray((new QuestionProvider())->toArrayWithoutImages());

        $this->assertFalse($checker->shouldDownloadQuestionImage($question));

    }

    public function testShouldDownloadAnswerTrue(): void
    {
        $checker = new DownloadChecker();
        $question = Question::fromArray((new QuestionProvider())->toArrayWithImages());

        foreach ($question->getAnswers() as $answer) {
            $this->assertTrue($checker->shouldDownloadAnswerImage($answer));
        }

        $question = Question::fromArray((new QuestionProvider())->toArrayWithImagesOnlyInAnswers());
        foreach ($question->getAnswers() as $answer) {
            $this->assertTrue($checker->shouldDownloadAnswerImage($answer));
        }
    }

    public function testShouldDownloadFalse(): void
    {
        $downloadChecker = new DownloadChecker();
        $ticket = Ticket::fromArray((new TicketProvider())->withoutImages());

        $this->assertFalse($downloadChecker->shouldDownload($ticket));
    }
    public function testShouldDownloadTrue(): void
    {
        $downloadChecker = new DownloadChecker();
        $ticket = Ticket::fromArray((new TicketProvider())->withImages());

        $this->assertTrue($downloadChecker->shouldDownload($ticket));
    }

}
