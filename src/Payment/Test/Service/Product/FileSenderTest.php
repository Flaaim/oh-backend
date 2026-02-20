<?php

namespace App\Payment\Test\Service\Product;

use App\Payment\Entity\Email as UserEmail;
use App\Payment\Service\Delivery\Product\FileSender;
use App\Product\Entity\File as EntityFile;
use App\Shared\Domain\ValueObject\RootPath;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\Mailer\Exception\TransportException;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Part\DataPart;
use Symfony\Component\Mime\Part\File;
use Twig\Environment;

class FileSenderTest extends TestCase
{
    public function testSuccess()
    {
        $email = new UserEmail('test@app.ru');
        $subject = 'subject';
        $file = $this->getFile();
        $file->mergeRoot($this->getRootPath());
        $template ='mail/template.html.twig';

        $twig = $this->createMock(Environment::class);
        $logger = $this->createMock(LoggerInterface::class);

        $message = (new Email())->subject($subject)->to($email->getValue())->html(
            $twig->render($template)
        )->addPart(
            new DataPart(new File($file->getFile()))
        );

        $mailer = $this->createMock(MailerInterface::class);
        $mailer->expects($this->once())->method('send')->with(
            $this->equalTo($message),
        )->willReturnCallback(static function ($message) use ($twig, $email, $subject, $file) {
            /** @var Email $message */
            self::assertEquals([new Address($email->getValue())], $message->getTo());
            self::assertEquals($subject, $message->getSubject());
            self::assertEquals($twig->render('mail/template.html.twig'), $message->getHtmlBody());
            self::assertEquals([new DataPart(new File($file->getFile()))], $message->getAttachments());
        });


        $productSender = new FileSender($mailer, $twig, $logger);
        $productSender->send($email, $subject, $file->getFile(), $template);
    }

    public function testFailed(): void
    {
        $email = new UserEmail('test@app.ru');
        $subject = 'subject';
        $file = $this->getFile();
        $file->mergeRoot($this->getRootPath());
        $template ='mail/template.html.twig';

        $mailer = $this->createMock(MailerInterface::class);
        $twig = $this->createMock(Environment::class);
        $logger = $this->createMock(LoggerInterface::class);

        $mailer->expects($this->once())->method('send')->willThrowException(new TransportException());

        $productSender = new FileSender($mailer, $twig, $logger);

        $this->expectException(TransportException::class);
        $productSender->send($email, $subject, $file->getFile(), $template);
    }

    private function getFile(): EntityFile
    {
        $tempFile = tempnam(sys_get_temp_dir(), 'template');
        return new EntityFile(basename($tempFile));
    }

    private function getRootPath(): RootPath
    {
        return new RootPath(sys_get_temp_dir());
    }
}