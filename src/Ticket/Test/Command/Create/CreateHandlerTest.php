<?php

namespace App\Ticket\Test\Command\Create;

use App\Flusher;
use App\Ticket\Command\Create\Request\Command;
use App\Ticket\Command\Create\Request\Handler;
use App\Ticket\Entity\Ticket;
use App\Ticket\Entity\TicketRepository;
use App\Ticket\Service\ImageDownloader\DownloadChecker;
use App\Ticket\Service\ImageDownloader\ImageDownloader;
use App\Ticket\Service\ImageDownloader\PathConverter;
use App\Ticket\Service\ImageDownloader\PathManager;
use PHPUnit\Framework\TestCase;

class CreateHandlerTest extends TestCase
{
    public function testSuccessWithoutImage(): void
    {
        $repository = $this->createMock(TicketRepository::class);
        $flusher = $this->createMock(Flusher::class);
        $pathManager = $this->createMock(PathManager::class);
        $downloadChecker = $this->createMock(DownloadChecker::class);
        $imageDownloader = $this->createMock(ImageDownloader::class);
        $pathConverter = $this->createMock(PathConverter::class);

        $command = new Command($this->getTicketArrayProvider());
        $downloadChecker->expects($this->once())->method('shouldDownload')->willReturn(false);
        $repository->expects($this->once())->method('addOrUpdate')->with(
            $this->isInstanceOf(Ticket::class),
        );
        $flusher->expects($this->once())->method('flush');

        $handler = new Handler($repository, $flusher, $pathManager, $downloadChecker, $imageDownloader, $pathConverter);
        $handler->handle($command);
    }

    private function getTicketArrayProvider(): array
    {
        return [
            'id' => '90f3b701-3602-4050-a27f-a246ee980fe7',
            'name' => null,
            'cipher' => null,
            'status' => 'nonactive',
            'price' => null,
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
                    'image' => '',
                    'answers' => [
                        [
                            'id' => '87c1f2f9-395b-4517-afb8-9b2146660445',
                            'text' => '"Запрещается прикасаться. Опасно"',
                            'isCorrect' => true,
                            'image' => 'http://olimpoks.chukk.ru:82/QuestionImages/81703c22-7f8e-4a37-9591-e0d59f4fc093/8/1.jpg'
                        ],
                        [
                            'id' => 'a9c8a646-4cd6-481d-bb93-1fdc9da1e782',
                            'text' => '"Осторожно. Возможно травмирование рук"',
                            'isCorrect' => true,
                            'image' => 'http://olimpoks.chukk.ru:82/QuestionImages/81703c22-7f8e-4a37-9591-e0d59f4fc093/8/2.jpg'
                        ],
                        [
                            'id' => '67e194bd-2526-40c7-9eac-6e64e99419f4',
                            'text' => '"Работать в защитных перчатках"',
                            'isCorrect' => true,
                            'image' => 'http://olimpoks.chukk.ru:82/QuestionImages/81703c22-7f8e-4a37-9591-e0d59f4fc093/8/3.jpg'
                        ],
                        [
                            'id' => 'ed70bd9b-f661-439a-99ac-82595324d2f8',
                            'text' => '"Опасно. Едкие и коррозионные вещества"',
                            'isCorrect' => true,
                            'image' => 'http://olimpoks.chukk.ru:82/QuestionImages/81703c22-7f8e-4a37-9591-e0d59f4fc093/8/4.jpg'
                        ]
                    ]
                ],
                [
                    'id' => '7c7f0af42f28486484010dccaf6942c8',
                    'number' => '3',
                    'text' => 'Установите правильную последовательность действий работника в случае обнаружения пожара.',
                    'image' => 'http://olimpoks.chukk.ru:82/QuestionImages/c37111/7c7f0af4-2f28-4864-8401-0dccaf6942c8/8/1.jpg',
                    'answers' => [
                        [
                            'id' => '50d0ebdd-6c82-4fca-aa6c-6f7054c54dde',
                            'text' => 'Сообщить о возгорании по телефону в пожарную охрану',
                            'isCorrect' => true,
                            'image' => ''
                        ],
                        [
                            'id' => 'deafa7ab-76c5-4f24-afa2-de5bce32aacc',
                            'text' => 'Принять меры по эвакуации людей',
                            'isCorrect' => true,
                            'image' => ''
                        ],
                        [
                            'id' => 'b46f838d-3a5a-4104-887c-6ae04652076c',
                            'text' => 'Приступить к тушению пожара в начальной стадии (при отсутствии угрозы жизни и здоровью людей)',
                            'isCorrect' => true,
                            'image' => ''
                        ]
                    ]
                ],
                [
                    'id' => '27b164d193474fb08555e08d5f9c2393',
                    'number' => '4',
                    'text' => 'Какие работники допускаются к работе с кислотой, щелочью и свинцом?',
                    'image' => 'http://olimpoks.chukk.ru:82/QuestionImages/c37111/27b164d1-9347-4fb0-8555-e08d5f9c2393/8/1.jpg',
                    'answers' => [
                        [
                            'id' => 'ac5c09df-fc6a-4ece-865d-7d12db0f6e74',
                            'text' => 'Работники, которым выданы памятки по обращению с кислотой, щелочью и свинцом',
                            'isCorrect' => false,
                            'image' => ''
                        ],
                        [
                            'id' => 'abccbc1e-4013-45c2-9e21-0965322c7bc1',
                            'text' => 'Работники, достигшие возраста 16 лет',
                            'isCorrect' => false,
                            'image' => ''
                        ],
                        [
                            'id' => 'ca4f8fc7-b5ff-4fb9-9ef5-e0dc2603365b',
                            'text' => 'Работники, прошедшие специальное обучение',
                            'isCorrect' => true,
                            'image' => ''
                        ],
                        [
                            'id' => '0eec148c-6877-4653-bb04-4eacedbce22f',
                            'text' => 'Работники, имеющие минимальные представления и знания по применению кислоты, щелочи и свинца',
                            'isCorrect' => false,
                            'image' => ''
                        ]
                    ]
                ],
                [
                    'id' => '5f2c5383dfb841898721e977ad747171',
                    'number' => '5',
                    'text' => 'Каким документом определяются специальные меры предосторожности при выполнении работ с аккумуляторными батареями во избежание отравления свинцом и его соединениями?',
                    'image' => 'http://olimpoks.chukk.ru:82/QuestionImages/c37111/5f2c5383-dfb8-4189-8721-e977ad747171/8/1.jpg',
                    'answers' => [
                        [
                            'id' => 'a767507f-e7db-435c-b737-10635f882ba5',
                            'text' => 'Памяткой для работников организации',
                            'isCorrect' => false,
                            'image' => ''
                        ],
                        [
                            'id' => 'bdfb9645-2c42-465a-8b69-d68ea77b8829',
                            'text' => 'Коллективным договором',
                            'isCorrect' => false,
                            'image' => ''
                        ],
                        [
                            'id' => 'ee942067-7d6b-4f81-8c57-5f8a56d8436e',
                            'text' => 'Инструкцией по эксплуатации и ремонту аккумуляторных батарей',
                            'isCorrect' => true,
                            'image' => ''
                        ],
                        [
                            'id' => '1906ba77-a589-4bff-8bba-3120c3558c8c',
                            'text' => 'Положением об организации работы по охране труда на предприятии',
                            'isCorrect' => false,
                            'image' => ''
                        ]
                    ]
                ],
                [
                    'id' => '7f1c0a2bb52a4ff5876e9ab636cd1eb7',
                    'number' => '6',
                    'text' => 'Что соответствует требованиям безопасности при хранении кислоты, предназначенной для приготовления электролита?',
                    'image' => 'http://olimpoks.chukk.ru:82/QuestionImages/c37111/7f1c0a2b-b52a-4ff5-876e-9ab636cd1eb7/8/1.jpg',
                    'answers' => [
                        [
                            'id' => 'd5eeaed8-bbfb-4b90-82aa-95aa1216a85a',
                            'text' => 'Кислота должна храниться в стеклянных бутылях с притертыми пробками, снабженных бирками с названием кислоты',
                            'isCorrect' => true,
                            'image' => ''
                        ],
                        [
                            'id' => 'da7e3776-b766-4ba8-9621-79a42930265e',
                            'text' => 'Кислота должна храниться в металлических емкостях, снабженных бирками, на которых указана валентность кислотного остатка',
                            'isCorrect' => false,
                            'image' => ''
                        ],
                        [
                            'id' => '62eb60c1-21c9-44e5-80cf-8bb9b82cbfb6',
                            'text' => 'Бутыли с кислотой и порожние бутыли должны находиться в одном помещении с аккумуляторными батареями',
                            'isCorrect' => false,
                            'image' => ''
                        ],
                        [
                            'id' => 'e28074d7-b146-425f-a2fe-89bdbbfd7479',
                            'text' => 'Бутыли следует устанавливать на полу на диэлектрический коврик',
                            'isCorrect' => false,
                            'image' => ''
                        ]
                    ]
                ],
            ]
        ];
    }
}