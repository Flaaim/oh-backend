<?php

namespace App\TelegramBot\Entity;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class ChatRepository
{
    private EntityRepository $repo;
    private EntityManagerInterface $em;
    public function __construct(EntityManagerInterface $em)
    {
        $repo = $em->getRepository(Chat::class);
        $this->repo = $repo;
        $this->em = $em;
    }

    public function insertIgnore(Chat $chat): int
    {
        $qb = $this->em->createQueryBuilder();
        $sql = 'INSERT IGNORE INTO chats (id, chat_id, name, created_at) VALUES (:id, :chat_id, :name, :created_at)';

        return (int)$qb->getEntityManager()
            ->getConnection()
            ->executeStatement(
                $sql,
                [
                    'id' => $chat->getId()->getValue(),
                    'chat_id' => $chat->getChatId(),
                    'name' => $chat->getName(),
                    'created_at' => $chat->getCreatedAt()->format('Y-m-d H:i:s')
                ]
            );
    }
}