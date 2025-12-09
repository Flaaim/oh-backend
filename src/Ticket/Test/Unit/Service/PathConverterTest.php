<?php

namespace App\Ticket\Test\Unit\Service;

use App\Ticket\Entity\Ticket;
use App\Ticket\Service\ImageDownloader\PathConverter;
use App\Ticket\Service\ImageDownloader\UrlBuilder;
use App\Ticket\Test\Builder\TicketProvider;
use PHPUnit\Framework\TestCase;

class PathConverterTest extends TestCase
{
    public function testConvertSuccess(): void
    {
        $converter = new PathConverter($this->getUrlBuilder());
        $ticket = Ticket::fromArray((new TicketProvider())->withImages());

        $converter->convertQuestionImages($ticket, $this->getResultDownload()['questions']);

        $this->assertEquals($this->expectedResult(), $ticket->getQuestions()->toArray());
    }
    private function getUrlBuilder(): UrlBuilder
    {
        return new UrlBuilder('http://localhost/QuestionImages');
    }

    private function getResultDownload(): array
    {
        return [
           'questions' => [
               [
                   "question_id" => "49336cb09422414399ec69aa582f60e4",
                   "url" => "http://olimpoks.chukk.ru:82/QuestionImages/c37111/49336cb0-9422-4143-99ec-69aa582f60e4/8/1.jpg",
                   "status" => "success",
                   "path" => "/app/config/common/../../public/QuestionImages/90f3b701-3602-4050-a27f-a246ee980fe7/49336cb09422414399ec69aa582f60e4/1.jpg"
               ],
               [
                   "question_id" => "81703c227f8e4a379591e0d59f4fc093",
                   "url" => "http://olimpoks.chukk.ru:82/QuestionImages/c37111/49336cb0-9422-4143-99ec-69aa582f60e4/8/1.jpg",
                   "status" => "success",
                   "path" => "/app/config/common/../../public/QuestionImages/90f3b701-3602-4050-a27f-a246ee980fe7/81703c227f8e4a379591e0d59f4fc093/2.jpg"
               ]
           ],

        ];
    }

    private function expectedResult(): array
    {
        $ticket = Ticket::fromArray((new TicketProvider())->withDownloadImages());
        return $ticket->getQuestions()->toArray();
    }
}
