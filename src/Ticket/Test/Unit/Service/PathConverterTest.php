<?php

namespace App\Ticket\Test\Unit\Service;

use App\Ticket\Entity\Ticket;
use App\Ticket\Service\ImageDownloader\PathConverter;
use App\Ticket\Service\ImageDownloader\UrlBuilder;
use PHPUnit\Framework\TestCase;

class PathConverterTest extends TestCase
{
    public function testConvertSuccess(): void
    {
        $converter = new PathConverter($this->getUrlBuilder());
        $ticket = Ticket::fromArray($this->getArrayData());

        $converter->convertQuestionImages($ticket, $this->getResultDownload()['questions']);
        $converter->convertAnswerImages($ticket, $this->getResultDownload()['answers']);

        $this->assertEquals($this->expectedResult(), $ticket->getQuestions()->toArray());
    }
    private function getUrlBuilder(): UrlBuilder
    {
        return new UrlBuilder('http://localhost/QuestionImages');
    }
    private function getArrayData(): array
    {

        return [
            'id' => '90f3b701-3602-4050-a27f-a246ee980fe7',
            'name' => null,
            'cipher' => null,
            'questions' => [
                [
                    'id' => '49336cb09422414399ec69aa582f60e4',
                    'number' => '1',
                    'text' => 'Какое требование предъявляется к кабелю переносной лампы, применяемой в работе с кислотными аккумуляторными батареями?',
                    'image' => 'http://olimpoks.chukk.ru:82/QuestionImages/c37111/49336cb0-9422-4143-99ec-69aa582f60e4/8/1.jpg',
                    'answers' => [
                        [
                            'id' => '4efec305-0468-4ff3-b4a1-ad9645ad6e96',
                            'text' => 'Запрещается прикасаться. Опасно',
                            'isCorrect' => true,
                            'image' => 'http://olimpoks.chukk.ru:82/QuestionImages/81703c22-7f8e-4a37-9591-e0d59f4fc093/8/1.jpg'
                        ]
                    ]
                ]
            ]
        ];

    }

    private function getResultDownload(): array
    {
        return [
           'questions' => [
               [
                   "question_id" => "49336cb09422414399ec69aa582f60e4",
                   "url" => "http://olimpoks.chukk.ru:82/QuestionImages/c37111/49336cb0-9422-4143-99ec-69aa582f60e4/8/1.jpg",
                   "status" => "success",
                   "path" => "/app/config/common/../../public/QuestionImages/fb87c290-07de-4183-b67b-d4d697ff7f04/969f4b34-43e4-4f76-bc0e-b02f8633734e/1.jpg"
               ]
           ],
            'answers' => [
                [
                    "answer_id" => "4efec305-0468-4ff3-b4a1-ad9645ad6e96",
                    "url" => "http://olimpoks.chukk.ru:82/QuestionImages/81703c22-7f8e-4a37-9591-e0d59f4fc093/8/1.jpg",
                    "status" => "success",
                    "path" => "/app/config/common/../../public/QuestionImages/fb87c290-07de-4183-b67b-d4d697ff7f04/969f4b34-43e4-4f76-bc0e-b02f8633734e/4efec305-0468-4ff3-b4a1-ad9645ad6e96/1.jpg"
                ]
            ]
        ];
    }

    private function expectedResult(): array
    {
        $expectedArrayData = [
            'id' => '90f3b701-3602-4050-a27f-a246ee980fe7',
            'name' => null,
            'cipher' => null,
            'questions' => [
                [
                    'id' => '49336cb09422414399ec69aa582f60e4',
                    'number' => '1',
                    'text' => 'Какое требование предъявляется к кабелю переносной лампы, применяемой в работе с кислотными аккумуляторными батареями?',
                    'image' => 'http://localhost/QuestionImages/fb87c290-07de-4183-b67b-d4d697ff7f04/969f4b34-43e4-4f76-bc0e-b02f8633734e/1.jpg',
                    'answers' => [
                        [
                            'id' => '4efec305-0468-4ff3-b4a1-ad9645ad6e96',
                            'text' => 'Запрещается прикасаться. Опасно',
                            'isCorrect' => true,
                            'image' => 'http://localhost/QuestionImages/fb87c290-07de-4183-b67b-d4d697ff7f04/969f4b34-43e4-4f76-bc0e-b02f8633734e/4efec305-0468-4ff3-b4a1-ad9645ad6e96/1.jpg'
                        ]
                    ]
                ]
            ]
        ];
        $ticket = Ticket::fromArray($expectedArrayData);
        return $ticket->getQuestions()->toArray();
    }
}
