<?php

namespace App\Product\Command\Upsert;

class Command
{
    public function __construct(
        public string $name,
        public string $cipher,
        public float $amount,
        public string $path,
        public string $course
    ){}
}