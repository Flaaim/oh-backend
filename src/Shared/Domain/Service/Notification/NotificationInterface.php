<?php

declare(strict_types=1);

namespace App\Shared\Domain\Service\Notification;

interface NotificationInterface
{
    public function notify(object $event): void;
}
