<?php

namespace App\Access\Entity;


interface AccessRepository
{
    public function get(AccessId $id): Access;

    public function create(Access $access): void;
}