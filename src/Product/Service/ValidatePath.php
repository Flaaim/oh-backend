<?php

namespace App\Product\Service;

class ValidatePath
{
    public function validate(string $path): int
    {
        $status = preg_match('/^[a-zA-Z]+\/\d+$/', $path);
        if(false === $status) {
            throw new \DomainException('Error validate path');
        }
        return $status;
    }
}