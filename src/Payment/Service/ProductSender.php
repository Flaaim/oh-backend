<?php

namespace App\Payment\Service;

use App\Payment\Entity\Email;
use Symfony\Component\Mailer\Exception\TransportException;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;



class ProductSender
{
    private MailerInterface $mailer;
    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }
    public function send(Email $email): void
    {
        $message = new \Symfony\Component\Mime\Email();
        $message->subject('Образец документов');
        $message->to($email->getValue());
        $message->text(
            'some text'
        );
        try{
            $this->mailer->send($message);
        } catch (TransportExceptionInterface $exception) {
            throw new TransportException($exception->getMessage());
        }

    }
}