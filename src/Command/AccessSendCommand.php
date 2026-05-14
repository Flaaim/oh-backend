<?php

declare(strict_types=1);

namespace App\Command;

use App\Access\Entity\DTO\OpenAccessDTO;
use App\Payment\Entity\Email;
use App\Payment\Service\Delivery\Access\AccessSender;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Twig\Environment;

class AccessSendCommand extends Command
{
    public function configure(): void
    {
        $this->setName('product:send_access');
        $this->setDescription('Send access message');
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $container = require __DIR__ . '/../../config/container.php';

            $productSender = new AccessSender(
                $container->get(MailerInterface::class),
                $twig = $container->get(Environment::class),
                $container->get(LoggerInterface::class)
            );
            $openAccessDTO = new OpenAccessDTO(
                'some_url',
                'Ответы',
                'OT 218.9'
            );
            $productSender->send(
                new Email('test@app.ru'),
                $openAccessDTO,
                'mail/template_access.html.twig'
            );
            return self::SUCCESS;
        } catch (TransportExceptionInterface $e) {
            $output->writeln('<error>' . $e->getMessage() . '</error>');
            return self::FAILURE;
        }
    }
}
