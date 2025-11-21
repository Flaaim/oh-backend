<?php

namespace App\TelegramBot\Entity;

use App\Shared\ValueObject\Id;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity]
#[ORM\Table(name: 'chats')]
class Chat
{
    #[ORM\Id]
    #[ORM\Column(type: 'id', unique: true)]
    private Id $id;
    #[ORM\Column(type: 'bigint', unique: true)]
    private int $chatId;
    #[ORM\Column(type: 'string', length: 255)]
    private string $name;
    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $createdAt;

    public function __construct(Id $id, int $chatId, string $name, \DateTimeImmutable $createdAt)
    {
        $this->id = $id;
        $this->chatId = $chatId;
        $this->name = $name;
        $this->createdAt = $createdAt;
    }

    public function getName(): string
    {
        return $this->name;
    }
    public function getChatId(): int
    {
        return $this->chatId;
    }

    public function getId(): Id
    {
        return $this->id;
    }
    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

}