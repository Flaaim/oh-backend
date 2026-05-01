<?php

namespace App\Payment\Service\Delivery\Access;

use App\Payment\Entity\Email;
use App\Payment\Service\Delivery\ProductDeliveryInterface;
use App\Product\Entity\Product;
use App\Access\Command\OpenAccess\Handler as OpenAccessHandler;
use App\Access\Command\OpenAccess\Command as OpenAccessCommand;
use App\Product\Entity\Type;

class AccessDelivery implements ProductDeliveryInterface
{
    public function __construct(
        private readonly OpenAccessHandler $handler,
        private readonly AccessSender $accessSender
    ) {
    }
    public function deliver(string $email, Product $product): void
    {
        $accessDTO = $this->handler->handle(new OpenAccessCommand($email, $product->getId()->getValue()));
        $this->accessSender->send(new Email($email), $accessDTO, 'mail/template_access.html.twig');
    }

    public function supports(string $type): bool
    {
        return Type::Access->value === $type;
    }
}
