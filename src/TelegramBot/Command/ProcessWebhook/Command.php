<?php

namespace App\TelegramBot\Command\ProcessWebhook;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Required;

class Command
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Collection(
            fields: [
                'update_id' => new Required([
                    new Assert\NotBlank,
                ]),
            ], allowExtraFields: true
        )]
        public readonly array $updateData
    )
    {}
}