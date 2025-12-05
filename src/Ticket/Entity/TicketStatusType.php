<?php

namespace App\Ticket\Entity;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

class TicketStatusType extends StringType
{
    const NAME = 'ticket_status';
    public function convertToDatabaseValue($value, AbstractPlatform $platform): mixed
    {
        return $value instanceof Status ? $value->getValue() : $value;
    }
    public function convertToPHPValue($value, AbstractPlatform $platform): ?Status
    {
        return !empty($value) ? Status::create((string)$value) : null;
    }
    public function getName(): string
    {
        return self::NAME;
    }
}
