<?php

declare(strict_types=1);

namespace App\Access\Exception;

final class AccessExpiredException extends \DomainException
{
    private string $productId;
    public function __construct(string $productId, string $message = null)
    {
        parent::__construct($message);
        $this->productId = $productId;
    }
    public function getProductId(): string
    {
        return $this->productId;
    }
}
