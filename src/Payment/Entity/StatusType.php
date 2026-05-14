<?php

declare(strict_types=1);

namespace App\Payment\Entity;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

final class StatusType extends StringType
{
    public const NAME = 'status';

    public function convertToDatabaseValue($value, AbstractPlatform $platform): mixed
    {
        return $value instanceof Status ? $value->getValue() : $value;
    }
    public function convertToPHPValue($value, AbstractPlatform $platform): ?Status
    {
        return !empty($value) ? new Status((string)$value) : null;
    }

    /**
     * @param array<array-key, mixed> $column
     */
    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        $columnData = $column;
        $columnData['length'] = 36;
        $columnData['fixed'] = true;

        return $platform->getStringTypeDeclarationSQL($columnData);
    }
}
