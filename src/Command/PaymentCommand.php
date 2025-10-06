<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PaymentCommand extends Command
{
    public function configure(): void
    {
        $this->setName('payment');
        $this->setDescription('Payment command');
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln("Payment command...");
        return self::SUCCESS;
    }
}