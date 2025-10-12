<?php

namespace App\Payment\Test\Service;

use App\Payment\Service\ProductSender;
use App\Product\Entity\Currency;
use App\Product\Entity\Price;
use App\Product\Entity\Product;
use App\Shared\ValueObject\Id;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Mailer\Exception\TransportException;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use App\Payment\Entity\Email as UserEmail;
use Symfony\Component\Mime\Part\DataPart;
use Symfony\Component\Mime\Part\File;

class ProductSenderTest extends TestCase
{
    public function testSuccess()
    {
        $product = $this->getProduct();
        $email = new UserEmail('test@app.ru');
        $text = 'Спасибо за покупку! Образцы документов мы приложили к этому письму';
        $subject = $product->getName();


        $message = (new Email())->subject($subject)->to($email->getValue())->html($text)->addPart(
            new DataPart(new File($product->getPath()))
        );

        $mailer = $this->createMock(MailerInterface::class);
        $mailer->expects($this->once())->method('send')->with(
            $this->equalTo($message),
        )->willReturnCallback(static function ($message) use ($text, $email, $subject) {
            /** @var Email $message */
            self::assertEquals([new Address($email->getValue())], $message->getTo());
            self::assertEquals($subject, $message->getSubject());
            self::assertEquals($text, $message->getHtmlBody());

        });

        $productSender = new ProductSender($mailer);
        $productSender->send($email, $product);
    }

    public function testFailed(): void
    {
        $product = $this->getProduct();
        $email = new UserEmail('test@app.ru');
        $mailer = $this->createMock(MailerInterface::class);
        $mailer->expects($this->once())->method('send')->willThrowException(new TransportException());

        $productSender = new ProductSender($mailer);

        $this->expectException(TransportException::class);
        $productSender->send($email, $product);
    }

    private function getProduct(): Product
    {
        $tempFile = tempnam(sys_get_temp_dir(), 'test');
        file_put_contents($tempFile, 'test content');
        return new Product(
            Id::generate(),
            'Образцы документов СИЗ',
            new Price(450.00, new Currency('RUB')),
            $tempFile,
            '1'
        );
    }
}