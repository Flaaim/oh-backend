<?php

namespace App\TelegramBot\Command\SetWebhook;

use Symfony\Component\Validator\Constraints as Assert;
class Command
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Url]
        public string $url
    ){}
}