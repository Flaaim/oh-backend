<?php

namespace App\Product\Entity;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;
use Override;

class FileType extends StringType
{
    public const string NAME = 'file';
    #[Override]
    public function convertToDatabaseValue($value, AbstractPlatform $platform): mixed
    {
        return $value instanceof File ? $value->getValue() : $value;
    }
    #[Override]
    public function convertToPHPValue($value, AbstractPlatform $platform): ?File
    {
        return !empty($value) ? new File((string)$value) : null;
    }
    /**
     * @param array<array-key, mixed> $column
     * @param AbstractPlatform $platform
     * @return string
     */
    #[Override]
    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        $columnData = $column;
        $columnData['length'] = 36;
        $columnData['fixed'] = true;

        return $platform->getStringTypeDeclarationSQL($columnData);
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
