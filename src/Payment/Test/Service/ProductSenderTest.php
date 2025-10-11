<?php

namespace App\Payment\Test\Service;

use App\Payment\Service\ProductSender;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Mailer\Exception\TransportException;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use App\Payment\Entity\Email as UserEmail;

class ProductSenderTest extends TestCase
{
    public function testSuccess()
    {
        $email = new UserEmail('test@app.ru');
        $text = 'some text';
        $subject = 'Образец документов';

        $message = (new Email())->subject($subject)->to($email->getValue())->text($text);

        $mailer = $this->createMock(MailerInterface::class);
        $mailer->expects($this->once())->method('send')->with(
            $this->equalTo($message),
        )->willReturnCallback(static function ($message) use ($text, $email, $subject) {
            /** @var Email $message */
            self::assertEquals([new Address($email->getValue())], $message->getTo());
            self::assertEquals($subject, $message->getSubject());
            self::assertEquals($text, $message->getTextBody());
        });

        $productSender = new ProductSender($mailer);
        $productSender->send($email);
    }

    public function testFailed(): void
    {
        $email = new UserEmail('test@app.ru');
        $mailer = $this->createMock(MailerInterface::class);
        $mailer->expects($this->once())->method('send')->willThrowException(new TransportException());

        $productSender = new ProductSender($mailer);
        $this->expectException(TransportException::class);
        $productSender->send($email);
    }
}