<?php

declare(strict_types=1);

namespace App\Access\Test\Unit\Command\Stream;

use App\Access\Command\Stream\Command;
use App\Access\Command\Stream\Handler;
use App\Access\Entity\Access;
use App\Access\Entity\AccessRepository;
use App\Access\Service\UuidConverter;
use App\Shared\Domain\ProductQuery\ProductQueryDTO;
use App\Shared\Domain\ProductQuery\ProductQueryInterface;
use App\Shared\Domain\ValueObject\RootPath;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

/**
 * @internal
 */
class HandlerTest extends TestCase
{
    private string $token;
    private string $productId;
    private AccessRepository $accesses;
    private ProductQueryInterface $productQuery;
    private Command $command;
    private Handler $handler;
    protected function setUp(): void
    {
        $this->token = Uuid::uuid4()->toString();
        $this->productId = Uuid::uuid4()->toString();

        $uuidConverter = new UuidConverter();

        $this->command = new Command(
            $uuidConverter->encode($this->token),
            $uuidConverter->encode($this->productId),
        );

        $this->handler = new Handler(
            $this->accesses = $this->createMock(AccessRepository::class),
            $uuidConverter,
            $this->productQuery = $this->createMock(ProductQueryInterface::class),
            new RootPath(sys_get_temp_dir())
        );
    }
    public function testSuccess(): void
    {
        $tempFile = $this->tempFile();
        $this->accesses->expects(self::once())->method('getByToken')
            ->with(
                self::equalTo($this->token),
            )->willReturn($access = $this->createMock(Access::class));

        $this->productQuery->expects(self::once())->method('getProduct')->with(
            self::equalTo($this->productId)
        )->willReturn(new ProductQueryDTO(
            $this->productId,
            $access->getName(),
            $access->getCipher(),
            $tempFile
        ));

        $path = $this->handler->handle($this->command);

        self::assertFileExists($path);
        self::assertFileEquals('/tmp/' . $tempFile, $path);
    }

    public function testExpired(): void
    {

        $this->accesses->expects(self::once())->method('getByToken')
            ->with(
                self::equalTo($this->token),
            )->willReturn($access = $this->createMock(Access::class));

        $access->expects(self::once())->method('isExpired')->willReturn(true);

        self::expectException(\DomainException::class);
        self::expectExceptionMessage('Срок действия доступа к файлу истек...');

        $this->handler->handle($this->command);
    }

    public function testFileNotExists(): void
    {
        $this->accesses->expects(self::once())->method('getByToken')
            ->with(
                self::equalTo($this->token),
            )->willReturn($access = $this->createMock(Access::class));

        $access->expects(self::once())->method('isExpired')->willReturn(false);

        $this->productQuery->expects(self::once())->method('getProduct')
            ->with(
                self::equalTo($this->productId),
            )->willReturn(new ProductQueryDTO(
                $this->productId,
                $access->getName(),
                $access->getCipher(),
                '/tmp/file_not_found.txt'
            ));

        self::expectException(\DomainException::class);
        self::expectExceptionMessage('Файл не найден...');
        $this->handler->handle($this->command);
    }

    public function tempFile(): string
    {
        $tempFile = tempnam(sys_get_temp_dir(), 'test');
        return basename($tempFile);
    }
}
