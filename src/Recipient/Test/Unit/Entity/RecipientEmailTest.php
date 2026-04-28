final <?php

namespace App\Recipient\Test\Unit\Entity;

use App\Payment\Entity\Email;
use PHPUnit\Framework\TestCase;

class RecipientEmailTest extends TestCase
{
    public function testSuccess(): void
    {
        $email = new Email($value = 'user@app.test');
        $this->assertSame($value, $email->getValue());
    }

    public function testCase(): void
    {
        $value = 'user@app.ru';
        $email = new Email(mb_strtoupper($value));
        $this->assertSame($value, $email->getValue());
    }

    public function testEmpty(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new Email('');
    }

    public function testInvalid(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new Email('invalid');
    }
}