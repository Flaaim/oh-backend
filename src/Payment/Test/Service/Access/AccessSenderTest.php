<?php

namespace App\Payment\Test\Service\Access;

use App\Access\Entity\DTO\OpenAccessDTO;
use App\Payment\Entity\Email;
use App\Payment\Service\Delivery\Access\AccessSender;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\Mailer\Exception\TransportException;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Twig\Environment;
use Twig\Loader\ArrayLoader;

class AccessSenderTest extends TestCase
{
    public function testSuccess(): void
    {
        $email = new Email('some@email.ru');
        $accessDto = new OpenAccessDTO(
            'some_url',
            'name of access',
            'ОТ 218.1'
        );
        $template = 'mail/template_access.html.twig';
        $loader = new ArrayLoader([
            $template => "<a href='{{ link }}'>Ссылка</a>",
        ]);

        $mailer = $this->createMock(MailerInterface::class);
        $twig = new Environment($loader);
        $logger = $this->createMock(LoggerInterface::class);

        $message = (new \Symfony\Component\Mime\Email())
            ->subject($accessDto->name)
            ->to($email->getValue())
            ->html($twig->render($template, ['link' => $accessDto->url]));


        $mailer->expects($this->once())->method('send')->with(
            $this->equalTo($message)
        )->willReturnCallback(static function ($message) use ($accessDto, $email, $twig) {
            self::assertEquals($accessDto->name, $message->getSubject());
            self::assertEquals([new Address($email->getValue())], $message->getTo());
            self::assertEquals("<a href='some_url'>Ссылка</a>", $message->getHtmlBody());
        });

        $sender = new AccessSender($mailer, $twig, $logger);

        $sender->send($email, $accessDto, $template);
    }

    public function testFailed(): void
    {
        $email = new Email('some@email.ru');
        $accessDto = new OpenAccessDTO(
            'some_url',
            'name of access',
            'ОТ 218.1'
        );
        $template = 'mail/template_access.html.twig';

        $mailer = $this->createMock(MailerInterface::class);
        $twig = $this->createMock(Environment::class);
        $logger = $this->createMock(LoggerInterface::class);

        $mailer->expects($this->once())->method('send')->willThrowException(new TransportException('Something went wrong.'));

        $sender = new AccessSender($mailer, $twig, $logger);

        self::expectException(TransportException::class);
        self::expectExceptionMessage('Something went wrong.');
        $sender->send($email, $accessDto, $template);
    }
}
