<?php

declare(strict_types=1);

namespace App\Access\Test\Unit\Command\GetAccess;

use App\Access\Command\GetAccess\Command;
use App\Access\Command\GetAccess\Handler;
use App\Access\Entity\Access;
use App\Access\Entity\AccessRepository;
use App\Access\Exception\AccessExpiredException;
use App\Access\Service\UuidConverter;
use App\Access\Test\Builder\AccessBuilder;
use App\Shared\Domain\ProductQuery\ProductQueryDTO;
use App\Shared\Domain\ProductQuery\ProductQueryInterface;
use App\Shared\Domain\ValueObject\RootPath;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

/**
 * @internal
 */
final class HandlerTest extends TestCase
{
    private string $uuid;
    private Command $command;
    private Handler $handler;
    private ProductQueryInterface $productQuery;
    private AccessRepository $accesses;
    protected function setUp(): void
    {
        $this->uuid = Uuid::uuid4()->toString();
        $uuidConverter = new UuidConverter();
        $this->command = new Command(
            $uuidConverter->encode($this->uuid)
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
                self::equalTo($this->uuid),
            )->willReturn($access = (new AccessBuilder())->build());

        $this->productQuery->expects(self::once())->method('getProduct')->willReturn(
            new ProductQueryDTO(
                Uuid::uuid4()->toString(),
                'Ответы на вопросы тестирования',
                'OT 1558.1',
                $tempFile
            )
        );

        $dto = $this->handler->handle($this->command);

        self::assertFileExists('/tmp/' . $tempFile);
        self::assertEquals(22, strlen($dto->productId));
        self::assertEquals($access->getEmail()->getValue(), $dto->email);
        self::assertEquals($access->getCipher(), $dto->cipher);
        self::assertEquals($access->getName(), $dto->name);
        self::assertFalse($access->isExpired());
    }

    public function testExpired(): void
    {
        $this->accesses->expects(self::once())->method('getByToken')
            ->with(
                self::equalTo($this->uuid),
            )->willReturn($access = (new AccessBuilder())->expired()->build());

        self::expectException(AccessExpiredException::class);
        self::expectExceptionMessage('Срок действия доступа к файлу истек...');

        try {
            throw new AccessExpiredException($access->getProductId(), 'Срок действия доступа к файлу истек...');
        } catch (AccessExpiredException $e) {
            self::assertEquals('b38e76c0-ac23-4c48-85fd-975f32c8801f', $e->getProductId());
        }

        $this->handler->handle($this->command);
    }
    public function testFileNotExists(): void
    {
        $this->accesses->expects(self::once())->method('getByToken')
            ->with(
                self::equalTo($this->uuid),
            )->willReturn($access = $this->createMock(Access::class));

        $access->expects(self::once())->method('isExpired')->willReturn(false);

        $this->productQuery->expects(self::once())->method('getProduct')->willReturn(
            new ProductQueryDTO(
                Uuid::uuid4()->toString(),
                'Ответы на вопросы тестирования',
                'OT 1558.1',
                'ppe/templates.txt'
            )
        );

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
