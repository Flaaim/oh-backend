<?php

namespace App\Product\Entity;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;
use Override;

class PriceType extends StringType
{
    public const string NAME = 'price';
    #[Override]
    public function convertToDatabaseValue($value, AbstractPlatform $platform): mixed
    {
        return $value instanceof Price ? $value->getValue() : $value;
    }
    #[Override]
    public function convertToPHPValue($value, AbstractPlatform $platform): ?Price
    {
        return !empty($value) ? new Price((float)$value, new Currency('RUB')) : null;
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
    #[Override]
    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        $columnData = $column;
        $columnData['length'] = 36;
        $columnData['fixed'] = true;

        return $platform->getStringTypeDeclarationSQL($columnData);
    }
}
