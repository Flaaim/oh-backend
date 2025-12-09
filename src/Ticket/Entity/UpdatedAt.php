<?php

namespace App\Ticket\Entity;

use DateTimeImmutable;
use DomainException;

class UpdatedAt
{
    private DateTimeImmutable $value;
    public function __construct(string $value)
    {
        $value = DateTimeImmutable::createFromFormat('d.m.Y', trim($value));
        if($value === false) {
            throw new DomainException('Format updatedAt must be a d.m.Y');
        }
        $errors = DateTimeImmutable::getLastErrors();
        if ($errors && $errors['warning_count'] > 0) {
            throw new DomainException('updated at must be a valid date in format dd.mm.yyyy');
        }
        $this->value = $value;
    }
    public function getValue(): DateTimeImmutable
    {
        return $this->value;
    }
    public function format(string $format): string
    {
        return $this->value->format($format);
    }
}