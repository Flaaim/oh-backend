<?php

namespace App\Product\Entity;

enum Type: string
{
    case File = 'file';
    case Access = 'access';
}
