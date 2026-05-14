<?php

declare(strict_types=1);

namespace App\Recipient\Entity;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

class RecipientIdType extends StringType
{
    public const NAME = 'recipient_id';

    public function convertToDatabaseValue($value, AbstractPlatform $platform): mixed
    {
        return $value instanceof RecipientId ? $value->getValue() : $value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?RecipientId
    {
        return !empty($value) ? new RecipientId((string)$value) : null;
    }
    public function getName(): string
    {
        return self::NAME;
    }
    /**
     * @param array<array-key, mixed> $column
     * @param AbstractPlatform $platform
     * @return string
     */
    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        $columnData = $column;
        $columnData['length'] = 36;
        $columnData['fixed'] = true;

        return $platform->getStringTypeDeclarationSQL($columnData);
    }
}
