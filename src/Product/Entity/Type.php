<?php

declare(strict_types=1);

namespace App\Product\Entity;

enum Type: string
{
    case File = 'file';
    case Access = 'access';
}
