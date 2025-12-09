<?php

namespace App\Ticket\Test\Unit\Service;

use App\Ticket\Service\ImageDownloader\DownloadChecker;
use App\Ticket\Service\ImageDownloader\ImageDownloader;
use App\Ticket\Service\ImageDownloader\PathManager;
use App\Ticket\Test\Builder\QuestionCollectionBuilder;
use App\Ticket\Test\Builder\TicketBuilder;
use DMS\PHPUnitExtensions\ArraySubset\ArraySubsetAsserts;
use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;

class ImageDownloadTest extends TestCase
{
    use ArraySubsetAsserts;
    public function testSuccess(): void
    {
        $ticket = (new TicketBuilder())->withQuestions(
            (new QuestionCollectionBuilder())->withImages()
        )->build();

        $imageDownloader = new ImageDownloader(
            new PathManager(sys_get_temp_dir()),
            new Client(),
            new DownloadChecker()
        );

        $result = $imageDownloader->download($ticket);
        self::assertNotEmpty($result);
        self::assertArraySubset([
            'questions' => [
                [
                    'question_id' => '49336cb09422414399ec69aa582f60e4',
                ],
                [
                    'question_id' => '7c7f0af42f28486484010dccaf6942c8'
                ]
            ],
            'answers' => [
                [
                    'answer_id' => '30604d45-60be-4316-8f97-58f2cfa18fda',
                ],
                [
                    'answer_id' => '71a6e6e9-6215-41e6-a5ac-745f86182730',
                ]
            ],
        ], $result);
    }
}