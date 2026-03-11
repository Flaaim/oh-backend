<?php

namespace App\Product\Command\RecountPrice;

class Command
{
    public function __construct(
        public readonly string $type,
        public readonly string $productId,
    ){
    }
}