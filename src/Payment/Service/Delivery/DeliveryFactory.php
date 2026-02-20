<?php

namespace App\Payment\Service\Delivery;

use App\Product\Entity\ProductRepository;
use App\Shared\Domain\Service\Payment\PaymentWebhookDataInterface;
use App\Shared\Domain\ValueObject\Id;

class DeliveryFactory
{
    public function __construct(
        private readonly ProductRepository $products,
        /** @var array<ProductDeliveryInterface> */
        private array $deliverers,
    ){
        foreach ($this->deliverers as $delivery) {
            if(!$delivery instanceof ProductDeliveryInterface){
                throw new \DomainException('Delivery is not a valid array element.');
            }
        }
    }
    public function createDelivery(PaymentWebhookDataInterface $paymentWebHookData): void
    {
        $productId = $paymentWebHookData->getMetadata('productId');
        $email = $paymentWebHookData->getMetadata('email');

        if(!$productId || !$email){
            throw new \DomainException('Missing required metadata in webhook');
        }

        $product = $this->products->get(new Id($productId));

        foreach ($this->deliverers as $delivery) {
            if($delivery->supports($paymentWebHookData->getMetadata('type'))){
                $delivery->deliver($email, $product);
                return;
            }
        }
        throw new \DomainException('Delivery is not a valid array element.');
    }
}