<?php

namespace App\Ticket\Test\Unit\Service;

use App\Ticket\Entity\Question;
use App\Ticket\Entity\Ticket;
use App\Ticket\Service\ImageDownloader\DownloadChecker;
use PHPUnit\Framework\TestCase;

class DownloadCheckerTest extends TestCase
{
    public function testShouldDownloadQuestionTrue(): void
    {
        $checker = new DownloadChecker();
        $question = Question::fromArray($this->questionAndAnswersNotEmpty());

        $this->assertTrue($checker->shouldDownloadQuestionImage($question));

        $question = Question::fromArray($this->questionNotEmptyAnswersEmpty());
        $this->assertTrue($checker->shouldDownloadQuestionImage($question));

        $question = Question::fromArray($this->questionEmptyAnswersNotEmpty());
        $this->assertTrue($checker->shouldDownloadQuestionImage($question));
    }


    public function testShouldDownloadQuestionFalse(): void
    {
        $checker = new DownloadChecker();
        $question = Question::fromArray($this->questionEmptyAnswersEmpty());

        $this->assertFalse($checker->shouldDownloadQuestionImage($question));

    }

    public function testShouldDownloadAnswerTrue(): void
    {
        $checker = new DownloadChecker();
        $question = Question::fromArray($this->questionAndAnswersNotEmpty());

        foreach ($question->getAnswers() as $answer) {
            $this->assertTrue($checker->shouldDownloadAnswerImage($answer));
        }

        $question = Question::fromArray($this->questionEmptyAnswersNotEmpty());
        foreach ($question->getAnswers() as $answer) {
            $this->assertTrue($checker->shouldDownloadAnswerImage($answer));
        }
    }

    public function testShouldDownloadFalse(): void
    {
        $downloadChecker = new DownloadChecker();
        $ticket = Ticket::fromArray($this->ticketEmptyImages());

        $this->assertFalse($downloadChecker->shouldDownload($ticket));
    }
    public function testShouldDownloadTrue(): void
    {
        $downloadChecker = new DownloadChecker();
        $ticket = Ticket::fromArray($this->ticketNotEmptyImages());

        $this->assertTrue($downloadChecker->shouldDownload($ticket));
    }
    private function questionNotEmptyAnswersEmpty(): array
    {
        return [
                'id' => '49336cb09422414399ec69aa582f60e4',
                'number' => '1',
                'text' => 'Какое требование предъявляется к кабелю переносной лампы, применяемой в работе с кислотными аккумуляторными батареями?',
                'image' => 'http://olimpoks.chukk.ru:82/QuestionImages/c37111/49336cb0-9422-4143-99ec-69aa582f60e4/8/1.jpg',
                'answers' => [
                    [
                        "id" => "30604d45-60be-4316-8f97-58f2cfa18fda",
                        "text" => "Кабель должен быть в кислостойком шланге",
                        "isCorrect" => true,
                        "image" => ""
                    ],
                    [
                        "id" => "71a6e6e9-6215-41e6-a5ac-745f86182730",
                        "text" => "Кабель должен иметь не более 3 скруток",
                        "isCorrect" => false,
                        "image" => ""
                    ]
                ],

        ];
    }
    private function questionAndAnswersNotEmpty(): array
    {
        return [
            'id' => '49336cb09422414399ec69aa582f60e4',
            'number' => '1',
            'text' => 'Какое требование предъявляется к кабелю переносной лампы, применяемой в работе с кислотными аккумуляторными батареями?',
            'image' => 'http://olimpoks.chukk.ru:82/QuestionImages/c37111/49336cb0-9422-4143-99ec-69aa582f60e4/8/1.jpg',
            'answers' => [
                [
                    "id" => "30604d45-60be-4316-8f97-58f2cfa18fda",
                    "text" => "Кабель должен быть в кислостойком шланге",
                    "isCorrect" => true,
                    "image" => "http://olimpoks.chukk.ru:82/QuestionImages/81703c22-7f8e-4a37-9591-e0d59f4fc093/8/1.jpg"
                ],
                [
                    "id" => "71a6e6e9-6215-41e6-a5ac-745f86182730",
                    "text" => "Кабель должен иметь не более 3 скруток",
                    "isCorrect" => false,
                    "image" => "http://olimpoks.chukk.ru:82/QuestionImages/81703c22-7f8e-4a37-9591-e0d59f4fc093/8/2.jpg"
                ]
            ],

        ];
    }

    private function questionEmptyAnswersEmpty(): array
    {
        return [
            'id' => '49336cb09422414399ec69aa582f60e4',
            'number' => '1',
            'text' => 'Какое требование предъявляется к кабелю переносной лампы, применяемой в работе с кислотными аккумуляторными батареями?',
            'image' => '',
            'answers' => [
                [
                    "id" => "30604d45-60be-4316-8f97-58f2cfa18fda",
                    "text" => "Кабель должен быть в кислостойком шланге",
                    "isCorrect" => true,
                    "image" => ""
                ],
                [
                    "id" => "71a6e6e9-6215-41e6-a5ac-745f86182730",
                    "text" => "Кабель должен иметь не более 3 скруток",
                    "isCorrect" => false,
                    "image" => ""
                ]
            ],

        ];
    }

    private function questionEmptyAnswersNotEmpty(): array
    {
        return [
            'id' => '49336cb09422414399ec69aa582f60e4',
            'number' => '1',
            'text' => 'Какое требование предъявляется к кабелю переносной лампы, применяемой в работе с кислотными аккумуляторными батареями?',
            'image' => '',
            'answers' => [
                [
                    "id" => "30604d45-60be-4316-8f97-58f2cfa18fda",
                    "text" => "Кабель должен быть в кислостойком шланге",
                    "isCorrect" => true,
                    "image" => "http://olimpoks.chukk.ru:82/QuestionImages/81703c22-7f8e-4a37-9591-e0d59f4fc093/8/1.jpg"
                ],
                [
                    "id" => "71a6e6e9-6215-41e6-a5ac-745f86182730",
                    "text" => "Кабель должен иметь не более 3 скруток",
                    "isCorrect" => false,
                    "image" => "http://olimpoks.chukk.ru:82/QuestionImages/81703c22-7f8e-4a37-9591-e0d59f4fc093/8/2.jpg"
                ]
            ],

        ];
    }

    private function ticketEmptyImages(): array
    {
        return [
            'id' => '90f3b701-3602-4050-a27f-a246ee980fe7',
            'name' => null,
            'cipher' => null,
            'status' => 'nonactive',
            'questions' => [
                [
                    'id' => '49336cb09422414399ec69aa582f60e4',
                    'number' => '1',
                    'text' => 'Какое требование предъявляется к кабелю переносной лампы, применяемой в работе с кислотными аккумуляторными батареями?',
                    'image' => '',
                    'answers' => [
                        [
                            'id' => 'e587aa55-e210-40cf-80c1-4fab48209192',
                            'text' => 'Кабель должен быть в кислостойком шланге',
                            'isCorrect' => true,
                            'image' => ''
                        ],
                        [
                            'id' => '6fb5b3db-2a8b-4c3b-95a0-c9c13ddae3a4',
                            'text' => 'Кабель должен иметь не более 3 скруток',
                            'isCorrect' => false,
                            'image' => ''
                        ],
                        [
                            'id' => 'e6dac57f-2c3d-43f2-87b4-79ba3b92c8ae',
                            'text' => 'Кабель должен быть только в тканевой оплетке',
                            'isCorrect' => false,
                            'image' => ''
                        ],
                        [
                            'id' => 'e09aa4b3-0474-4e25-90ba-240565a62a0a',
                            'text' => 'Кабель должен быть длиной не более 1,5 м',
                            'isCorrect' => false,
                            'image' => ''
                        ]
                    ]
                ],
                [
                    'id' => '81703c227f8e4a379591e0d59f4fc093',
                    'number' => '2',
                    'text' => 'Установите соответствие между знаками безопасности и их значениями.',
                    'image' => '',
                    'answers' => [
                        [
                            'id' => '87c1f2f9-395b-4517-afb8-9b2146660445',
                            'text' => '"Запрещается прикасаться. Опасно"',
                            'isCorrect' => true,
                            'image' => ''
                        ],
                        [
                            'id' => 'a9c8a646-4cd6-481d-bb93-1fdc9da1e782',
                            'text' => '"Осторожно. Возможно травмирование рук"',
                            'isCorrect' => true,
                            'image' => ''
                        ],
                        [
                            'id' => '67e194bd-2526-40c7-9eac-6e64e99419f4',
                            'text' => '"Работать в защитных перчатках"',
                            'isCorrect' => true,
                            'image' => ''
                        ],
                        [
                            'id' => 'ed70bd9b-f661-439a-99ac-82595324d2f8',
                            'text' => '"Опасно. Едкие и коррозионные вещества"',
                            'isCorrect' => true,
                            'image' => ''
                        ]
                    ]
                ],
            ]
        ];
    }
    private function ticketNotEmptyImages(): array
    {
        return [
            'id' => '90f3b701-3602-4050-a27f-a246ee980fe7',
            'name' => null,
            'cipher' => null,
            'status' => 'nonactive',
            'questions' => [
                [
                    'id' => '49336cb09422414399ec69aa582f60e4',
                    'number' => '1',
                    'text' => 'Какое требование предъявляется к кабелю переносной лампы, применяемой в работе с кислотными аккумуляторными батареями?',
                    'image' => 'http://olimpoks.chukk.ru:82/QuestionImages/c37111/49336cb0-9422-4143-99ec-69aa582f60e4/8/1.jpg',
                    'answers' => [
                        [
                            'id' => 'e587aa55-e210-40cf-80c1-4fab48209192',
                            'text' => 'Кабель должен быть в кислостойком шланге',
                            'isCorrect' => true,
                            'image' => ''
                        ],
                        [
                            'id' => '6fb5b3db-2a8b-4c3b-95a0-c9c13ddae3a4',
                            'text' => 'Кабель должен иметь не более 3 скруток',
                            'isCorrect' => false,
                            'image' => ''
                        ],
                        [
                            'id' => 'e6dac57f-2c3d-43f2-87b4-79ba3b92c8ae',
                            'text' => 'Кабель должен быть только в тканевой оплетке',
                            'isCorrect' => false,
                            'image' => ''
                        ],
                        [
                            'id' => 'e09aa4b3-0474-4e25-90ba-240565a62a0a',
                            'text' => 'Кабель должен быть длиной не более 1,5 м',
                            'isCorrect' => false,
                            'image' => ''
                        ]
                    ]
                ],
                [
                    'id' => '81703c227f8e4a379591e0d59f4fc093',
                    'number' => '2',
                    'text' => 'Установите соответствие между знаками безопасности и их значениями.',
                    'image' => 'http://olimpoks.chukk.ru:82/QuestionImages/c37111/49336cb0-9422-4143-99ec-69aa582f60e4/8/1.jpg',
                    'answers' => [
                        [
                            'id' => '87c1f2f9-395b-4517-afb8-9b2146660445',
                            'text' => '"Запрещается прикасаться. Опасно"',
                            'isCorrect' => true,
                            'image' => ''
                        ],
                        [
                            'id' => 'a9c8a646-4cd6-481d-bb93-1fdc9da1e782',
                            'text' => '"Осторожно. Возможно травмирование рук"',
                            'isCorrect' => true,
                            'image' => ''
                        ],
                        [
                            'id' => '67e194bd-2526-40c7-9eac-6e64e99419f4',
                            'text' => '"Работать в защитных перчатках"',
                            'isCorrect' => true,
                            'image' => ''
                        ],
                        [
                            'id' => 'ed70bd9b-f661-439a-99ac-82595324d2f8',
                            'text' => '"Опасно. Едкие и коррозионные вещества"',
                            'isCorrect' => true,
                            'image' => ''
                        ]
                    ]
                ],
            ]
        ];
    }
}
