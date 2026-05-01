<?php

namespace App\Access\Exception;

class AccessExpiredException extends \DomainException
{
    private $productId;
    public function __construct(string $productId, string $message = null, \Exception $previous = null)
    {
        parent::__construct($message);
        $this->productId = $productId;
    }
    public function getProductId(): string
    {
        return $this->productId;
    }
}
