<?php

declare(strict_types=1);

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Messenger\EventListener\StopWorkerOnTimeLimitListener;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Transport\TransportInterface;
use Symfony\Component\Messenger\Worker;

final class ConsumeCommand extends Command
{
    protected static $defaultName = 'messenger:consume';
    protected static $defaultDescription = 'Consumes messages from the doctrine transport';

    private MessageBusInterface $bus;
    private TransportInterface $transport;

    public function __construct(MessageBusInterface $bus, TransportInterface $transport)
    {
        parent::__construct();
        $this->bus = $bus;
        $this->transport = $transport;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('<info>Воркер запущен и слушает очередь...</info>');

        $dispatcher = new EventDispatcher();

        $dispatcher->addSubscriber(new StopWorkerOnTimeLimitListener(55));

        $worker = new Worker(
            ['default' => $this->transport],
            $this->bus,
            $dispatcher
        );

        $worker->run();

        $output->writeln('<comment>Воркер успешно завершил работу по лимиту времени.</comment>');
        return Command::SUCCESS;
    }

}
