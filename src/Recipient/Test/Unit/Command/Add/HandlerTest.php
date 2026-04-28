final <?php

namespace App\Recipient\Test\Unit\Command\Add;

use App\Flusher;
use App\Recipient\Command\Add\Command;
use App\Recipient\Command\Add\Handler;
use App\Recipient\Entity\Email;
use App\Recipient\Entity\Recipient;
use App\Recipient\Entity\RecipientId;
use App\Recipient\Entity\RecipientRepository;
use PHPUnit\Framework\TestCase;

class HandlerTest extends TestCase
{
    private RecipientRepository $recipients;
    private Flusher $flusher;
    private Handler $handler;
    #[\Override]
    public function setUp(): void
    {
        $this->recipients = $this->createMock(RecipientRepository::class);
        $this->flusher = $this->createMock(Flusher::class);
        $this->handler = new Handler($this->recipients, $this->flusher);
    }

    public function testSuccess(): void
    {
        $command = new Command('test@app.ru');

        $this->recipients->expects(self::once())->method('findByEmail')->willReturn(null);

        $this->recipients->expects(self::once())->method('add')
        ->with($this->isInstanceOf(Recipient::class));

        $this->flusher->expects(self::once())->method('flush');

        $this->handler->handle($command);
    }

    public function testExists(): void
    {
        $command = new Command($email = 'test@app.ru');

        $this->recipients->expects(self::once())->method('findByEmail')->willReturn(
            new Recipient(
                RecipientId::generate(),
                new Email($email)
            )
        );
        $this->recipients->expects(self::never())->method('add');
        $this->flusher->expects(self::never())->method('flush');

        $this->handler->handle($command);
    }
}