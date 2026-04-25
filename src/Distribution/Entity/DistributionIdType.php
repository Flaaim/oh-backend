<?php

namespace App\Distribution\Entity;

use Doctrine\DBAL\Types\StringType;
use Doctrine\DBAL\Platforms\AbstractPlatform;

class DistributionIdType extends StringType
{
    public const NAME = 'distribution_id';

    public function convertToDatabaseValue($value, AbstractPlatform $platform): mixed
    {
        return $value instanceof DistributionId ? $value->getValue() : $value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?DistributionId
    {
        return !empty($value) ? new DistributionId((string)$value) : null;
    }
    public function getName(): string
    {
        return self::NAME;
    }
    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        $column['length'] = 36;
        $column['fixed'] = true; // CHAR вместо VARCHAR

        return $platform->getStringTypeDeclarationSQL($column);
    }
}