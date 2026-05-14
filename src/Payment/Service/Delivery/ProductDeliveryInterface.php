<?php

declare(strict_types=1);

namespace App\Payment\Service\Delivery;

use App\Product\Entity\Product;

interface ProductDeliveryInterface
{
    public function deliver(string $email, Product $product): void;

    public function supports(string $type): bool;
}
