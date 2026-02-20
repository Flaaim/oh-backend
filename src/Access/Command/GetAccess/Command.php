<?php

namespace App\Access\Command\GetAccess;

use Webmozart\Assert\Assert;

class Command
{
    public function __construct(
      public string $url
    ){
        Assert::stringNotEmpty($this->url);
    }
}