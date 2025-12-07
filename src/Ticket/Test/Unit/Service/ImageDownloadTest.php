<?php

namespace App\Ticket\Test\Unit\Service;

use App\Ticket\Service\ImageDownloader\DownloadChecker;
use App\Ticket\Service\ImageDownloader\ImageDownloader;
use App\Ticket\Service\ImageDownloader\PathManager;
use App\Ticket\Test\Builder\QuestionCollectionBuilder;
use App\Ticket\Test\Builder\TicketBuilder;
use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;

class ImageDownloadTest extends TestCase
{
    public function testSuccess(): void
    {
        $ticket = (new TicketBuilder())->withQuestions(
            (new QuestionCollectionBuilder())->build()
        )->build();

        $imageDownloader = new ImageDownloader(
            new PathManager(sys_get_temp_dir()),
            new Client(),
            new DownloadChecker()
        );

        $result = $imageDownloader->download($ticket);
        self::assertEquals([
            'questions' => [
                [
                    'question_id' => '49336cb09422414399ec69aa582f60e4',
                    'url' => 'https://www.gstatic.com/webp/gallery/1.jpg',
                    'status' => 'success',
                ],
                [
                    'question_id' => '81703c227f8e4a379591e0d59f4fc093',
                    'url' => '',
                    'status' => 'error',
                ],
                [
                    'question_id' => '7c7f0af42f28486484010dccaf6942c8',
                    'url' => 'https://www.gstatic.com/webp/gallery/2.jpg',
                    'status' => 'success',
                ],
            ],
            'answers' => [
                [
                    'answer_id' => 'a9c8a646-4cd6-481d-bb93-1fdc9da1e782',
                    'url' => 'https://www.gstatic.com/webp/gallery/4.jpg',
                    'status' => 'success',
                ],
                [
                    'answer_id' => '67e194bd-2526-40c7-9eac-6e64e99419f4',
                    'url' => 'https://www.gstatic.com/webp/gallery/1.jpg',
                    'status' => 'success',
                ],
                [
                    'answer_id' => 'ed70bd9b-f661-439a-99ac-82595324d2f8',
                    'url' => 'https://www.gstatic.com/webp/gallery/2.jpg',
                    'status' => 'success',
                ]
            ],
        ], $result);
    }
}