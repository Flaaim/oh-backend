<?php

declare(strict_types=1);

namespace App\Command;

use App\Payment\Entity\Email;
use App\Payment\Service\Delivery\Product\FileSender;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Twig\Environment;

final class FileSendCommand extends Command
{
    public function configure(): void
    {
        $this->setName('product:send_file');
        $this->setDescription('Send product message');
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $container = require __DIR__ . '/../../config/container.php';

            $productSender = new FileSender(
                $container->get(MailerInterface::class),
                $twig = $container->get(Environment::class),
                $container->get(LoggerInterface::class)
            );
            $tempFile = tempnam(sys_get_temp_dir(), 'template');
            $productSender->send(
                new Email('test@app.ru'),
                'Тестовое письмо',
                $tempFile,
                'mail/template_file.html.twig'
            );
            return self::SUCCESS;
        } catch (TransportExceptionInterface $e) {
            $output->writeln('<error>' . $e->getMessage() . '</error>');
            return self::FAILURE;
        }
    }
}
