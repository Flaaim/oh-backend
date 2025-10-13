<?php

namespace App\Command;



use App\Payment\Entity\Email;
use App\Payment\Service\ProductSender;
use App\Product\Entity\Currency;
use App\Product\Entity\File;
use App\Product\Entity\Price;
use App\Product\Entity\Product;
use App\Shared\Domain\TemplatePath;
use App\Shared\ValueObject\Id;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Mailer\EventListener\EnvelopeListener;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\MailerInterface;
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
        try{

        $container = require __DIR__ . '/../../config/container.php';

        $productSender = new ProductSender(
            $container->get(MailerInterface::class),
            new TemplatePath(sys_get_temp_dir())
        );
        $tempFile = tempnam(sys_get_temp_dir(), 'template');
        $productSender->send(
            new Email('test@app.ru'),
            new Product(
                Id::generate(),
                'Образцы документов СИЗ',
                new Price(450.00, new Currency('RUB')),
                new File(basename($tempFile)),
                '1'
            )
        );
            return self::SUCCESS;
        }catch (TransportExceptionInterface $e) {
            $output->writeln('<error>' . $e->getMessage() . '</error>');
            return self::FAILURE;
        }

    }
}