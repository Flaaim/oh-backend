<?php

namespace App\Payment\Service\Delivery\Product;

use App\Payment\Entity\Email;
use Psr\Log\LoggerInterface;
use Symfony\Component\Mailer\Exception\TransportException;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Part\DataPart;
use Symfony\Component\Mime\Part\File;
use Twig\Environment;


class FileSender
{
    private MailerInterface $mailer;
    private Environment $twig;
    private LoggerInterface $logger;
    public function __construct(MailerInterface $mailer, Environment $twig, LoggerInterface $logger)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
        $this->logger = $logger;
    }
    public function send(Email $email, string $subject, string $pathFile, string $template): void
    {
        $message = new \Symfony\Component\Mime\Email();
        $message->subject($subject);
        $message->to($email->getValue());
        $message->html(
            $this->twig->render($template)
        );

        $message->addPart(
            new DataPart(
                new File($pathFile)
            )
        );
        try{
            $this->mailer->send($message);
        } catch (TransportExceptionInterface $e) {
            $this->logger->error('Failed to send mail: ', ['error' => $e->getMessage()]);
            throw new TransportException($e->getMessage());
        }
    }

}