<?php

namespace App\Payment\Service\Delivery\Access;

use App\Access\Entity\DTO\OpenAccessDTO;
use App\Payment\Entity\Email;
use Psr\Log\LoggerInterface;
use Symfony\Component\Mailer\Exception\TransportException;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Twig\Environment;

class AccessSender
{
    public function __construct(
        private MailerInterface $mailer,
        private Environment $twig,
        private LoggerInterface $logger
    ) {
    }

    public function send(Email $email, OpenAccessDTO $accessDTO, string $template): void
    {
        $message = new \Symfony\Component\Mime\Email();
        $message->subject($accessDTO->name);
        $message->to($email->getValue());
        $message->html($this->twig->render($template, ['link' => $accessDTO->url]));
        try {
            $this->mailer->send($message);
        } catch (TransportExceptionInterface $e) {
            $this->logger->error('Failed to send mail: ', ['error' => $e->getMessage()]);
            throw new TransportException($e->getMessage());
        }
    }
}
