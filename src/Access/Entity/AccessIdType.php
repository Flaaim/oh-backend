<?php

namespace App\Access\Entity;

use Doctrine\DBAL\Types\StringType;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Override;

class AccessIdType extends StringType
{
    public const string NAME = 'access_id';

    public function convertToDatabaseValue($value, AbstractPlatform $platform): mixed
    {
        return $value instanceof AccessId ? $value->getValue() : $value;
    }
    #[Override]
    public function convertToPHPValue($value, AbstractPlatform $platform): ?AccessId
    {
        return !empty($value) ? new AccessId((string)$value) : null;
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
    #[\Override]
    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        $columnData = $column;
        $columnData['length'] = 36;
        $columnData['fixed'] = true;

        return $platform->getStringTypeDeclarationSQL($columnData);
    }
}
