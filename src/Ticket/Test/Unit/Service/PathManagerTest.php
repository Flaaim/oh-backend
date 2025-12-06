<?php

namespace App\Ticket\Test\Unit\Service;

use App\Ticket\Service\ImageDownloader\PathManager;
use PHPUnit\Framework\TestCase;


class PathManagerTest extends TestCase
{
    public function testBuildPathToTicket(): void
    {
        (new PathManager(sys_get_temp_dir()))
            ->forTicket('1234')
            ->create();
        $this->assertDirectoryExists(sys_get_temp_dir(). DIRECTORY_SEPARATOR . '1234');
    }
    public function testWrongOrderQuestion(): void
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Call forQuestion() before forTicket');
        (new PathManager(sys_get_temp_dir()))->forQuestion('1234');
    }
    public function testWrongOrderAnswer(): void
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Call forAnswer() before forQuestion()');
        (new PathManager(sys_get_temp_dir()))->forAnswer('1234');
    }
    public function testBuildPathToQuestion(): void
    {
        $builder = (new PathManager(sys_get_temp_dir()))
            ->forTicket('1234');
            $builder->create();

        $builder->forQuestion('1234')
            ->create();

        $this->assertDirectoryExists(sys_get_temp_dir() . DIRECTORY_SEPARATOR . '1234');
    }

    public function testBuildNestedPathToQuestion(): void
    {
        $builder = (new PathManager(sys_get_temp_dir()))
            ->forTicket('1234');
        $builder->create();

        $questions = ['1', '2'];
        $expectedPath = sys_get_temp_dir(). DIRECTORY_SEPARATOR . '1234';
        foreach ($questions as $question) {
            $builder->forQuestion($question)->create();
            $this->assertDirectoryExists($expectedPath . DIRECTORY_SEPARATOR . $question);
        }
    }

    public function testBuildNestedPathToAnswer(): void
    {
        $builder = (new PathManager(sys_get_temp_dir()))
            ->forTicket('1234');
        $builder->create();


        foreach ($this->getQuestions() as $question) {
            $builder->forQuestion($question['id'])->create();
            $expectedPath = '/tmp/1234' . DIRECTORY_SEPARATOR . $question['id'];
            $this->assertDirectoryExists($expectedPath);
                foreach ($question['answers'] as $answer) {
                    $builder->forAnswer($answer['id'])->create();
                    $expectedPath = '/tmp/1234/'. $question['id'] .'/'. $answer['id'];
                    $this->assertDirectoryExists($expectedPath);
                }
        }

    }
    public function testGetPath(): void
    {
        $builder = (new PathManager(sys_get_temp_dir()))->forTicket('1234');

        $this->assertEquals('/tmp/1234/image.jpg', $builder->getImagePath('image.jpg'));
        $this->assertEquals('/tmp/1234/image.jpg', $builder->getImagePath('/image.jpg'));
    }

    public function testDeleteTicketPathSuccess(): void
    {
        $builder = (new PathManager(sys_get_temp_dir()))->forTicket('1234');
        $builder->create();

        $builder->deleteTicket('1234');

        $this->assertDirectoryDoesNotExist(sys_get_temp_dir() . DIRECTORY_SEPARATOR . '1234');
    }
    public function testDeleteTicketPathFail(): void
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Call create() before forTicket');

        $builder = (new PathManager('some_path'));
        $builder->create();

        $builder->deleteTicket('1234');
    }
    public function testDeleteQuestionPathSuccess(): void
    {
        $builder = (new PathManager(sys_get_temp_dir()))
            ->forTicket('1234')
                ->forQuestion('1');

        $builder->create();

        $builder->deleteQuestion('1');
        $this->assertDirectoryExists(sys_get_temp_dir() . DIRECTORY_SEPARATOR . '1234');
        $this->assertDirectoryDoesNotExist(sys_get_temp_dir() . DIRECTORY_SEPARATOR . '1234/1');
    }

    public function testDeleteAnswerPathSuccess(): void
    {
        $builder = (new PathManager(sys_get_temp_dir()))
            ->forTicket('1234')
                ->forQuestion('1')
            ->forAnswer('3');

        $builder->create();

        $builder->deleteAnswer('3');
        $this->assertDirectoryDoesNotExist(sys_get_temp_dir() . DIRECTORY_SEPARATOR . '1234/1/3');
    }

    private function getQuestions(): array
    {
        return [
            [
                'id' => '1',
                'answers' => [
                    ['id' => '4'],
                    ['id' => '5'],
                ]
            ],
            [
                'id' => '2',
                'answers' => [
                    ['id' => '7'],
                    ['id' => '8'],
                ]
            ]
        ];
    }
}
