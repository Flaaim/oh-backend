<?php

namespace App\Ticket\Test\Command\Create\DownloadImages\CreatePath;

use App\Shared\Domain\ValueObject\Id;
use App\Ticket\Command\Create\DownloadImages\CreatePath\Command;
use App\Ticket\Command\Create\DownloadImages\CreatePath\Handler;
use App\Ticket\Service\ImageDownloader\PathManager;
use App\Ticket\Test\Builder\QuestionCollectionBuilder;
use App\Ticket\Test\Builder\TicketBuilder;
use PHPUnit\Framework\TestCase;

class CreatePathHandler extends TestCase
{
    public function testSuccess(): void
    {
        $command = new Command(
            (new TicketBuilder())->withId($id = new Id('8bac1e13-cef0-405b-8fcd-b05c4d394730'))->withQuestions((new QuestionCollectionBuilder())->build())->build()
        );
        $handler = new Handler(new PathManager(sys_get_temp_dir()));
        $handler->handle($command);
        self::assertDirectoryExists(sys_get_temp_dir(). DIRECTORY_SEPARATOR . $id);
    }


}