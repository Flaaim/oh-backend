<?php

namespace App\Recipient\Test\Unit\Entity;

use App\Recipient\Entity\Email;
use App\Recipient\Entity\Recipient;
use App\Recipient\Entity\RecipientId;
use PHPUnit\Framework\TestCase;

class RecipientTest extends TestCase
{
    public function testRecipient(): void
    {
        $recipient = new Recipient(
          $id = RecipientId::generate(),
          $email = new Email('test@app.ru')
        );

        self::assertSame($id, $recipient->getId());
        self::assertSame($email, $recipient->getEmail());
    }
}