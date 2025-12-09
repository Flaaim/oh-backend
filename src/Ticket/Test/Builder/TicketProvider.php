<?php

namespace App\Ticket\Test\Builder;

class TicketProvider
{
    private array $ticket = [];
    public function withoutImages(): array
    {
        $this->ticket = $this->ticketWithEmptyImages();
        return $this->ticket;
    }
    public function withImages(): array
    {
        $this->ticket = $this->ticketWithImages();
        return $this->ticket;
    }
    public function withDownloadImages(): array
    {
        $this->ticket = $this->ticketWithDownloadImages();
        return $this->ticket;
    }
    private function ticketWithEmptyImages(): array
    {
        return [
            'id' => '90f3b701-3602-4050-a27f-a246ee980fe7',
            'name' => 'Проверка знаний стропальщика',
            'cipher' => "ОТ 123.1",
            'status' => 'inactive',
            'updatedAt' => '28.11.2025',
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

    private function ticketWithImages(): array
    {
        return [
            'id' => '90f3b701-3602-4050-a27f-a246ee980fe7',
            'name' => 'Проверка знаний стропальщика',
            'cipher' => "ОТ 123.1",
            'status' => 'inactive',
            'updatedAt' => '28.11.2025',
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

    private function ticketWithDownloadImages(): array
    {
        return [
            'id' => '90f3b701-3602-4050-a27f-a246ee980fe7',
            'name' => 'Проверка знаний стропальщика',
            'cipher' => "ОТ 123.1",
            'status' => 'inactive',
            'updatedAt' => '28.11.2025',
            'questions' => [
                [
                    'id' => '49336cb09422414399ec69aa582f60e4',
                    'number' => '1',
                    'text' => 'Какое требование предъявляется к кабелю переносной лампы, применяемой в работе с кислотными аккумуляторными батареями?',
                    'image' => 'http://localhost/QuestionImages/90f3b701-3602-4050-a27f-a246ee980fe7/49336cb09422414399ec69aa582f60e4/1.jpg',
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
                    'image' => 'http://localhost/QuestionImages/90f3b701-3602-4050-a27f-a246ee980fe7/81703c227f8e4a379591e0d59f4fc093/2.jpg',
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