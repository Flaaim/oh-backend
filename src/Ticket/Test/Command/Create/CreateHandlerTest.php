<?php

namespace App\Ticket\Test\Command\Create;

use App\Flusher;
use App\Ticket\Command\Create\Command;
use App\Ticket\Command\Create\DownloadImages\Command as DownloadImagesCommand;
use App\Ticket\Command\Create\DownloadImages\Handler as DownloadImagesHandler;
use App\Ticket\Command\Create\Convert\Handler as ConvertHandler;
use App\Ticket\Command\Create\Convert\Command as ConvertCommand;
use App\Ticket\Command\Create\Handler;
use App\Ticket\Entity\Ticket;
use App\Ticket\Entity\TicketRepository;
use App\Ticket\Service\ImageDownloader\DownloadChecker;
use PHPUnit\Framework\TestCase;

class CreateHandlerTest extends TestCase
{
    public function testSuccessWithoutImage(): void
    {
        $repository = $this->createMock(TicketRepository::class);
        $flusher = $this->createMock(Flusher::class);
        $downloadChecker = $this->createMock(DownloadChecker::class);

        $command = new Command($this->getTicketArrayProvider());
        $downloadChecker->expects($this->once())->method('shouldDownload')->willReturn(false);

        $repository->expects($this->once())->method('addOrUpdate')->with(
            $this->isInstanceOf(Ticket::class),
        );

        $flusher->expects($this->once())->method('flush');

        $handler = new Handler(
            $repository,
            $flusher,
            $downloadChecker,
            $this->createMock(DownloadImagesHandler::class),
            $this->createMock(ConvertHandler::class)
        );

        $handler->handle($command);
    }
    public function testSuccessWithImages(): void
    {
        $repository = $this->createMock(TicketRepository::class);
        $flusher = $this->createMock(Flusher::class);
        $downloadChecker = $this->createMock(DownloadChecker::class);
        $downloadImagesHandler = $this->createMock(DownloadImagesHandler::class);
        $convertHandler = $this->createMock(ConvertHandler::class);

        $command = new Command($this->getTicketArrayProvider());

        $downloadChecker->expects($this->once())->method('shouldDownload')->willReturn(true);

        $downloadImagesHandler->expects($this->once())->method('handle')->with(
            $this->isInstanceOf(DownloadImagesCommand::class),
        )->willReturn($this->getResultDownload());

        $convertHandler->expects($this->once())->method('handle')->with(
            $this->isInstanceOf(ConvertCommand::class),
        );

        $repository->expects($this->once())->method('addOrUpdate')->with(
            $this->isInstanceOf(Ticket::class),
        );

        $flusher->expects($this->once())->method('flush');

        $handler = new Handler(
            $repository,
            $flusher,
            $downloadChecker,
            $downloadImagesHandler,
            $convertHandler
        );

        $handler->handle($command);
    }
    private function getTicketArrayProvider(): array
    {
        return [
            'id' => '90f3b701-3602-4050-a27f-a246ee980fe7',
            'name' => null,
            'cipher' => null,
            'status' => 'nonactive',
            'questions' => [
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
            ]
        ];
    }
    private function getResultDownload(): array
    {
       return  [
          'questions' => [
              [
                  'question_id' => '67e194bd-2526-40c7-9eac-6e64e99419f4',
                  'url' => 'http://olimpoks.chukk.ru:82/QuestionImages/c37111/49336cb0-9422-4143-99ec-69aa582f60e4/8/1.jpg',
                  'status' => 'success',
                  'path' => '1.jpg',
                  'downloaded_at' => new \DateTimeImmutable()
              ]
          ],
           'answers' => [
               [
                   'answer_id' => 'a9c8a646-4cd6-481d-bb93-1fdc9da1e782',
                   'url' => 'http://olimpoks.chukk.ru:82/QuestionImages/81703c22-7f8e-4a37-9591-e0d59f4fc093/8/1.jpg',
                   'status' => 'success',
                   'path' => '1.jpg',
                   'downloaded_at' => new \DateTimeImmutable()
               ],
               [
                   'answer_id' => '87c1f2f9-395b-4517-afb8-9b2146660445',
                   'url' => 'http://olimpoks.chukk.ru:82/QuestionImages/81703c22-7f8e-4a37-9591-e0d59f4fc093/8/2.jpg',
                   'status' => 'success',
                   'path' => '2.jpg',
                   'downloaded_at' => new \DateTimeImmutable()
               ],
               [
                   'answer_id' => '67e194bd-2526-40c7-9eac-6e64e99419f4',
                   'url' => 'http://olimpoks.chukk.ru:82/QuestionImages/81703c22-7f8e-4a37-9591-e0d59f4fc093/8/3.jpg',
                   'status' => 'success',
                   'path' => '3.jpg',
                   'downloaded_at' => new \DateTimeImmutable()
               ],
               [
                   'answer_id' => 'ed70bd9b-f661-439a-99ac-82595324d2f8',
                   'url' => 'http://olimpoks.chukk.ru:82/QuestionImages/81703c22-7f8e-4a37-9591-e0d59f4fc093/8/4.jpg',
                   'status' => 'success',
                   'path' => '4.jpg',
                   'downloaded_at' => new \DateTimeImmutable()
               ]
           ]
        ];
    }

}