<?php

namespace App\Access\Test\Unit\Command\GetAccess;

use App\Access\Command\GetAccess\Command;

use App\Access\Command\GetAccess\Handler;
use App\Access\Entity\Access;
use App\Access\Entity\AccessRepository;
use App\Access\Service\UuidConverter;
use App\Shared\Domain\ProductQuery\ProductQueryDTO;
use App\Shared\Domain\ProductQuery\ProductQueryInterface;
use App\Shared\Domain\ValueObject\RootPath;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class HandlerTest extends TestCase
{
    private readonly UuidConverter $uuidConverter;
    public function setUp(): void
    {
        $this->uuidConverter = new UuidConverter();
    }
    public function testSuccess(): void
    {
        $command = new Command(
            $this->uuidConverter->encode($uuid = Uuid::uuid4()->toString()),
        );
        $tempFile = $this->tempFile();

        $handler = new Handler(
            $accesses = $this->createMock(AccessRepository::class),
            new UuidConverter(),
            $productQuery = $this->createMock(ProductQueryInterface::class),
            $rootPath = new RootPath(sys_get_temp_dir()),
        );

        $accesses->expects(self::once())->method('getByToken')
            ->with(
                $this->equalTo($uuid),
            )->willReturn($access = $this->createMock(Access::class));

        $access->expects(self::once())->method('isExpired')->willReturn(false);
        $productQuery->expects(self::once())->method('getProduct')->willReturn(
            new ProductQueryDTO(
                Uuid::uuid4()->toString(),
                'Ответы на вопросы тестирования',
                'OT 1558.1',
                $tempFile
            )
        );

        $path = $handler->handle($command);

        self::assertFileExists($path);
        self::assertEquals('/tmp/'.$tempFile, $path);
    }

    public function testExpired(): void
    {
        $command = new Command(
            $this->uuidConverter->encode($uuid = Uuid::uuid4()->toString()),
        );

        $handler = new Handler(
            $accesses = $this->createMock(AccessRepository::class),
            new UuidConverter(),
            $this->createMock(ProductQueryInterface::class),
            new RootPath(sys_get_temp_dir()),
        );

        $accesses->expects(self::once())->method('getByToken')
            ->with(
                $this->equalTo($uuid),
            )->willReturn($access = $this->createMock(Access::class));

        $access->expects(self::once())->method('isExpired')->willReturn(true);

        self::expectException(\DomainException::class);
        self::expectExceptionMessage('Срок действия доступа к файлу истек...');
        $handler->handle($command);
    }
    public function testFileNotExists(): void
    {
        $command = new Command(
            $this->uuidConverter->encode($uuid = Uuid::uuid4()->toString()),
        );

        $handler = new Handler(
            $accesses = $this->createMock(AccessRepository::class),
            new UuidConverter(),
            $productQuery = $this->createMock(ProductQueryInterface::class),
            $rootPath = new RootPath(sys_get_temp_dir()),
        );

        $accesses->expects(self::once())->method('getByToken')
            ->with(
                $this->equalTo($uuid),
            )->willReturn($access = $this->createMock(Access::class));

        $access->expects(self::once())->method('isExpired')->willReturn(false);
        $productQuery->expects(self::once())->method('getProduct')->willReturn(
            new ProductQueryDTO(
                Uuid::uuid4()->toString(),
                'Ответы на вопросы тестирования',
                'OT 1558.1',
                'ppe/templates.txt'
            )
        );


        self::expectException(\DomainException::class);
        self::expectExceptionMessage('Файл не найден...');

        $handler->handle($command);
    }

    public function tempFile(): string
    {
        $tempFile = tempnam(sys_get_temp_dir(), 'test');
        return basename($tempFile);
    }
}