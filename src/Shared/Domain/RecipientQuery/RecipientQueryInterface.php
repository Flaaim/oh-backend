<?php

namespace App\Shared\Domain\RecipientQuery;

interface RecipientQueryInterface
{
    public function getIterable(?RecipientFilter $filter = null): iterable;
}
