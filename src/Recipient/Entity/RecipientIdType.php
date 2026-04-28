final <?php

namespace App\Recipient\Entity;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

class RecipientIdType extends StringType
{
    public const NAME = 'recipient_id';

    #[\Override]
    public function convertToDatabaseValue($value, AbstractPlatform $platform): mixed
    {
        return $value instanceof RecipientId ? $value->getValue() : $value;
    }

    #[\Override]
    public function convertToPHPValue($value, AbstractPlatform $platform): ?RecipientId
    {
        return !empty($value) ? new RecipientId((string)$value) : null;
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