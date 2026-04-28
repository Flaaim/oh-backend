final <?php

namespace App\Distribution\Test\Unit\Command;

use App\Distribution\Command\Create\Command;
use App\Distribution\Command\Create\Handler;
use App\Distribution\Entity\Distribution;
use App\Distribution\Entity\DistributionRepository;
use App\Flusher;
use App\Shared\Domain\Queue\Distribution\SendEmailBatchMessage;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;

class HandlerTest extends TestCase
{
    private readonly DistributionRepository $distributions;
    private readonly Flusher $flusher;
    private readonly MessageBusInterface $messageBus;
    private readonly Handler  $handler;
    #[\Override]
    public function setUp(): void
    {
        $this->distributions = $this->createMock(DistributionRepository::class);
        $this->flusher = $this->createMock(Flusher::class);
        $this->messageBus = $this->createMock(MessageBusInterface::class);

        $this->handler = new Handler($this->distributions, $this->flusher, $this->messageBus);
    }

    public function testSuccess(): void
    {
        $command = new Command('subject', 'id');

        $this->distributions->expects(self::once())->method('add')->with(
            $this->isInstanceOf(Distribution::class),
        );

        $this->flusher->expects(self::once())->method('flush');
        $this->messageBus->expects(self::once())
            ->method('dispatch')
            ->with(self::isInstanceOf(SendEmailBatchMessage::class))
            ->willReturn(new Envelope(new \stdClass()));


        $this->handler->handle($command);
    }

}