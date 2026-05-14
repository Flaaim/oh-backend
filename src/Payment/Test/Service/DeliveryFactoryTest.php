<?php

declare(strict_types=1);

namespace App\Payment\Test\Service;

use App\Payment\Service\Delivery\Access\AccessDelivery;
use App\Payment\Service\Delivery\DeliveryFactory;
use App\Payment\Service\Delivery\Product\FileDelivery;
use App\Product\Entity\ProductRepository;
use App\Product\Entity\Type;
use App\Shared\Domain\Service\Payment\PaymentWebhookDataInterface;
use App\Shared\Domain\ValueObject\Id;
use PHPUnit\Framework\TestCase;
use stdClass;
use Test\Functional\Payment\ProductBuilder;

/**
 * @internal
 */
final class DeliveryFactoryTest extends TestCase
{
    public function testSuccess(): void
    {
        $productId = '1252f161-259d-4390-8c7c-d2c27eaaaa71';
        $email = 'some@email.ru';

        $delivery = new DeliveryFactory(
            $products = $this->createMock(ProductRepository::class),
            [
                $fileDelivery = $this->createMock(FileDelivery::class),
                $accessDelivery = $this->createMock(AccessDelivery::class),
            ]
        );
        $paymentWebhookData = $this->createMock(PaymentWebhookDataInterface::class);

        $paymentWebhookData->expects(self::exactly(3))
            ->method('getMetadata')
            ->willReturnCallback(fn ($key) => match ($key) {
                'productId' => $productId,
                'email' => $email,
                'type' => Type::File->value,
                default => null
            });

        $id = new Id($productId);
        $products->expects(self::once())->method('get')->with($id)
            ->willReturn($product = (new ProductBuilder())->withId($id)->build());

        $fileDelivery->expects(self::once())->method('supports')->willReturn(true);
        $fileDelivery->expects(self::once())->method('deliver')->with(
            self::equalTo($email),
            self::equalTo($product)
        );

        $delivery->createDelivery($paymentWebhookData);
    }

    public function testInvalidDeliverElement(): void
    {
        self::expectException(\DomainException::class);
        self::expectExceptionMessage('Delivery is not a valid array element.');

        new DeliveryFactory(
            $this->createMock(ProductRepository::class),
            [new stdClass(), new stdClass()]
        );
    }
}
