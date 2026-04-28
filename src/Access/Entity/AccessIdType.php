final <?php

namespace App\Access\Entity;

use Doctrine\DBAL\Types\StringType;
use Doctrine\DBAL\Platforms\AbstractPlatform;

class AccessIdType extends StringType
{
    public const NAME = 'access_id';

    #[\Override]
    public function convertToDatabaseValue($value, AbstractPlatform $platform): mixed
    {
        return $value instanceof AccessId ? $value->getValue() : $value;
    }

    #[\Override]
    public function convertToPHPValue($value, AbstractPlatform $platform): ?AccessId
    {
        return !empty($value) ? new AccessId((string)$value) : null;
    }
    public function getName(): string
    {
        return self::NAME;
    }
    #[\Override]
    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        $column['length'] = 36;
        $column['fixed'] = true; // CHAR вместо VARCHAR

        return $platform->getStringTypeDeclarationSQL($column);
    }
}