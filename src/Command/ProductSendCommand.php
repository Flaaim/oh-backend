<?php

namespace App\Command;



use App\Payment\Entity\Email;
use App\Payment\Service\ProductSender;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Mailer\EventListener\EnvelopeListener;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport;
use Symfony\Component\Mime\Address;


class ProductSendCommand extends Command
{
    public function configure(): void
    {
        $this->setName('product:send');
        $this->setDescription('Send product message');
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {

        $transport = (new EsmtpTransport(
            'mailer',
            1025,

        ))
            ->setUsername('app')
            ->setPassword('secret');

        $mailer = new Mailer($transport);
        try{
            $mailer->send(
                (new \Symfony\Component\Mime\Email())
                    ->to('user@app.ru')
                    ->subject('Тестовое письмо')
                    ->from('asmin@test.ru')
                    ->html('Текст письма')
            );
            return self::SUCCESS;
        }catch (TransportExceptionInterface $e) {
            $output->writeln('<error>' . $e->getMessage() . '</error>');
            return self::FAILURE;
        }

    }
}