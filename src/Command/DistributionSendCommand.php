<?php

namespace App\Command;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
class DistributionSendCommand extends Command
{
    public function configure(): void
    {
        $this->setName('distribution:send');
        $this->setDescription('Send distribution');
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $client = new Client(['base_uri' => 'https://goapi.unisender.ru/ru/transactional/api/v1/']);
        $emails = ['flaeim@gmail.com', 'flaaim@list.ru'];
        $subject = 'test';
        $templateId = '30b1812e-3f6e-11f1-90b8-76fb82c9d6b5';

        $recipients = array_map(fn($email) => ['email' => $email], $emails);

        $requestBody =  [
            "message" => [
                'recipients' => $recipients,
                "template_id" => $templateId,
                "skip_unsubscribe" => 0,
                "subject" => "Unisender Go test email ",
                "from_name" => "John Smith",
            ],
        ];

        try{
            $client->request('POST', 'email/send.json', [
                'headers' => [
                    'X-API-KEY' => '6azj4xf5tp7kkkmqi6z1jsqokrse9z5c177tn6de',
                    'Content-Type' => 'application/json',
                ],
                'json' => $requestBody,
            ]);
            return self::SUCCESS;
        }catch (GuzzleException $e){
            $output->writeln('<error>' . $e->getMessage() . '</error>');
            return self::FAILURE;
        }

    }

}