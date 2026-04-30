<?php

namespace App\Recipient\Test\Unit\Command\Deactivate;

use App\Flusher;
use App\Recipient\Command\Deactivate\Command;
use App\Recipient\Command\Deactivate\Handler;
use App\Recipient\Entity\Email;
use App\Recipient\Entity\RecipientRepository;
use App\Recipient\Test\Builder\RecipientBuilder;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class HandlerTest extends TestCase
{
    /** @var RecipientRepository&MockObject  */
    private RecipientRepository $recipients;
    /** @var Flusher&MockObject  */
    private Flusher $flusher;
    private Handler $handler;
    public function setUp(): void
    {
        $this->recipients = $this->createMock(RecipientRepository::class);
        $this->flusher = $this->createMock(Flusher::class);

        $this->handler = new Handler($this->recipients, $this->flusher);
    }

    #[DataProvider('additionProvider')]
    public function testSuccess($email, $recipient): void
    {
        $command = new Command([$email]);

        $this->recipients->expects(self::once())->method('findAllByEmails')->willReturn([$recipient]);
        $this->flusher->expects(self::once())->method('flush');

        $this->handler->handle($command);

        self::assertFalse($recipient->isActive());
    }

    public function testEmpty(): void
    {
        $command = new Command(['test@app.ru']);

        $this->recipients->expects(self::once())->method('findAllByEmails')->willReturn([]);
        $this->flusher->expects(self::never())->method('flush');

        $this->handler->handle($command);
    }

    public static function additionProvider(): array
    {
        return [
            ['test@app.ru', (new RecipientBuilder())->withEmail(new Email('test@app.ru'))->build()],
            ['test1@app.ru', (new RecipientBuilder())->withEmail(new Email('test1@app.ru'))->build()],
            ['test2@app.ru', (new RecipientBuilder())->withEmail(new Email('test3@app.ru'))->build()]
        ];
    }
}