final <?php

namespace App\LeadManagement\Entity;

use Ramsey\Uuid\Uuid;
use Webmozart\Assert\Assert;

class LeadId
{
    private string $value;
    public function __construct(string $value)
    {
        Assert::uuid($value);
        $this->value = mb_strtolower($value);
    }
    public function getValue(): string
    {
        return $this->value;
    }


    public function __toString(): string
    {
        return $this->value;
    }
}