<?php

namespace App\Access\Test\Unit\Command\OpenAccess;

use App\Access\Command\OpenAccess\Command;
use App\Access\Command\OpenAccess\Handler;
use App\Access\Entity\Access;
use App\Access\Entity\AccessRepository;
use App\Access\Entity\Email;
use App\Flusher;
use App\Shared\Domain\ProductQuery\ProductQueryDTO;
use App\Shared\Domain\ProductQuery\ProductQueryInterface;
use PHPUnit\Framework\TestCase;

class HandlerTest extends TestCase
{
    public function testSuccess(): void
    {
        $email = new Email('test@email.ru');
        $productId = '95dd9b76-a090-4e14-8c33-6a8c249c2279';

        $command = new Command($email->getValue(), $productId);

        $handler = new Handler(
            $productQuery = $this->createMock(ProductQueryInterface::class),
            $accesses = $this->createMock(AccessRepository::class),
            $flusher = $this->createMock(Flusher::class)
        );

        $productQuery->expects($this->once())->method('getProduct')
            ->with($this->equalTo($productId))
            ->willReturn(new ProductQueryDTO(
                $productId,
                'Name',
                'ot1555.5'
            ));

        $accesses->expects($this->once())->method('create')->with(
            $this->isInstanceOf(Access::class),
        );

        $flusher->expects($this->once())->method('flush');

        $handler->handle($command);
    }

    public function testFailed(): void
    {
        $email = new Email('test@email.ru');
        $productId = '95dd9b76-a090-4e14-8c33-6a8c249c2279';

        $command = new Command($email->getValue(), $productId);

        $handler = new Handler(
            $productQuery = $this->createMock(ProductQueryInterface::class),
            $accesses = $this->createMock(AccessRepository::class),
            $flusher = $this->createMock(Flusher::class)
        );

        $productQuery->expects($this->once())->method('getProduct')
            ->with($this->equalTo($productId))
            ->willThrowException(new \DomainException('Product not found.'));

        $accesses->expects($this->never())->method('create');
        $flusher->expects($this->never())->method('flush');

        self::expectException(\DomainException::class);
        self::expectExceptionMessage('Product not found.');
        $handler->handle($command);
    }
}