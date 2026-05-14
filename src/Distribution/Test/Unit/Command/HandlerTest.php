<?php

declare(strict_types=1);

namespace App\Distribution\Test\Unit\Command;

use App\Distribution\Command\Create\Command;
use App\Distribution\Command\Create\Handler;
use App\Distribution\Entity\Distribution;
use App\Distribution\Entity\DistributionRepository;
use App\Flusher;
use App\Shared\Domain\Queue\Distribution\SendEmailBatchMessage;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * @internal
 */
final class HandlerTest extends TestCase
{
    /** @var DistributionRepository&MockObject  */
    private readonly DistributionRepository $distributions;
    /** @var Flusher&MockObject  */
    private readonly Flusher $flusher;
    /** @var MessageBusInterface&MockObject  */
    private readonly MessageBusInterface $messageBus;
    private readonly Handler $handler;
    protected function setUp(): void
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
            self::isInstanceOf(Distribution::class),
        );

        $this->flusher->expects(self::once())->method('flush');
        $this->messageBus->expects(self::once())
            ->method('dispatch')
            ->with(self::isInstanceOf(SendEmailBatchMessage::class))
            ->willReturn(new Envelope(new \stdClass()));


        $this->handler->handle($command);
    }
}
