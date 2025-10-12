<?php

namespace App\Payment\Service;

use App\Payment\Entity\Email;
use App\Product\Entity\Product;
use Symfony\Component\Mailer\Exception\TransportException;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Part\DataPart;
use Symfony\Component\Mime\Part\File;


class ProductSender
{
    private MailerInterface $mailer;
    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }
    public function send(Email $email, Product $product): void
    {
        $message = new \Symfony\Component\Mime\Email();
        $message->subject($product->getName());
        $message->to($email->getValue());
        $message->html(
            'Спасибо за покупку! Образцы документов мы приложили к этому письму'
        );
        $message->addPart(
            new DataPart(
                new File(
                    $product->getPath(),
                )
            )
        );
        try{
            $this->mailer->send($message);
        } catch (TransportExceptionInterface $e) {
            throw new TransportException($e->getMessage());
        }

    }
}