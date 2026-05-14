<?php

declare(strict_types=1);

namespace App\Product\Command\Upsert;

final class Response
{
    public function __construct(public string $productId)
    {
    }
}
