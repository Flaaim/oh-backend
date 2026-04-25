<?php

namespace App\Shared\Test\Unit\Queue\Distribution;

use App\Distribution\Entity\Distribution;
use App\Distribution\Entity\DistributionId;
use App\Distribution\Entity\DistributionRepository;
use App\Flusher;
use App\Shared\Domain\Queue\Distribution\SendEmailBatchHandler;
use App\Shared\Domain\Queue\Distribution\SendEmailBatchMessage;
use App\Shared\Domain\RecipientQuery\RecipientQueryInterface;
use App\Shared\Domain\Service\Distribution\DistributionInterface;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class SendEmailBatchHandlerTest extends TestCase
{
    private readonly DistributionRepository $distributions;
    private readonly RecipientQueryInterface $recipientQuery;
    private readonly LoggerInterface $logger;
    private readonly DistributionInterface $uniSender;
    private readonly Flusher $flusher;
    private SendEmailBatchHandler $handler;
    public function setUp(): void
    {
        $this->distributions = $this->createMock(DistributionRepository::class);
        $this->recipientQuery = $this->createMock(RecipientQueryInterface::class);
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->uniSender = $this->createMock(DistributionInterface::class);
        $this->flusher = $this->createMock(Flusher::class);

        $this->handler = new SendEmailBatchHandler(
            $this->distributions,
            $this->recipientQuery,
            $this->logger,
            $this->uniSender,
            $this->flusher
        );
    }

    public function testNotFound(): void
    {
        $distributionId = new DistributionId('d64e7923-23a2-4e4e-a1f3-f5411fd86bb1');
        $command = new SendEmailBatchMessage($distributionId);

        $this->distributions->expects(self::once())->method('findById')
            ->with($distributionId)
            ->willReturn(null);

        $this->recipientQuery->expects(self::never())->method('getIterable');
        $this->flusher->expects(self::never())->method('flush');
        $this->logger->expects(self::never())->method('info');

        self::expectException(\RuntimeException::class);
        self::expectExceptionMessage('Distribution not found.');
        $this->handler->handle($command);
    }

    public function testSuccess(): void
    {
        $distributionId = new DistributionId('d64e7923-23a2-4e4e-a1f3-f5411fd86bb1');
        $command = new SendEmailBatchMessage($distributionId);
        $distribution = new Distribution($distributionId, 'test-subject', 'template-id');

        $this->distributions->expects(self::once())->method('findById')->with($distributionId)->willReturn($distribution);

        $this->recipientQuery->expects(self::once())->method('getIterable')->willReturn([
            ['email' => 'test@app.ru'],
            ['email' => 'test1@app.ru'],
            ['email' => 'test2@app.ru'],
        ]);

        $this->flusher->expects(self::once())->method('flush');
        $this->logger->expects(self::once())->method('info');

        $this->handler->handle($command);

        self::assertTrue($distribution->isEnded());

    }

    public function testEmptyRecipients(): void
    {
        $distributionId = new DistributionId('d64e7923-23a2-4e4e-a1f3-f5411fd86bb1');
        $command = new SendEmailBatchMessage($distributionId);
        $distribution = new Distribution($distributionId, 'test-subject', 'template-id');

        $this->distributions->expects(self::once())->method('findById')->with($distributionId)->willReturn($distribution);

        $this->recipientQuery->expects(self::once())->method('getIterable')->willReturn([]);

        $this->uniSender->expects(self::never())->method('send');
        $this->flusher->expects(self::once())->method('flush');
        $this->logger->expects(self::once())->method('info');

        $this->handler->handle($command);

        self::assertTrue($distribution->isEnded());
    }

    public function testBatch(): void
    {
        $distributionId = new DistributionId('d64e7923-23a2-4e4e-a1f3-f5411fd86bb1');
        $command = new SendEmailBatchMessage($distributionId);
        $distribution = new Distribution($distributionId, 'test-subject', 'template-id');



        $this->distributions->method('findById')->willReturn($distribution);

        $recipients = [];
        for($i = 1; $i <= 150; $i++){
            $recipients[] = ['email' => "user{$i}@app.ru"];
        }

        $this->recipientQuery->expects(self::once())->method('getIterable')->willReturn($recipients);

        $callCount = 0;
        $this->uniSender->expects(self::exactly(2))->method('send')
            ->willReturnCallback(function (string $subject, array $recipients, string $templateId) use (&$callCount) {
                $callCount++;

                if($callCount === 1){
                    self::assertEquals('test-subject', $subject);
                    self::assertEquals('template-id', $templateId);
                    self::assertCount(100, $recipients);
                }


            });


        $this->flusher->expects(self::once())->method('flush');
        $this->logger->expects(self::once())->method('info');


        $this->handler->handle($command);
    }
}