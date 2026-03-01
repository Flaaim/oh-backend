<?php

namespace App\Access\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'access_sessions')]
class Session
{
    #[ORM\Id]
    #[ORM\Column(type:'string', length: 255, unique: true)]
    private string $token;
    #[ORM\Column(type: 'string', length: 64)]
    private string $sessionId;
    #[ORM\Column(type: 'string', length: 64)]
    private string $ip;
    #[ORM\Column(type: 'string', length: 45)]
    private string $userAgent;
    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $lastSeen;
    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $createdAt;

    public function __construct(string $token, string $sessionId, string $ip, string $userAgent)
    {
        $this->token = $token;
        $this->sessionId = $sessionId;
        $this->ip = $ip;
        $this->userAgent = $userAgent;
        $this->lastSeen = new \DateTimeImmutable();
        $this->createdAt = new \DateTimeImmutable();
    }
    public function isExpired(int $minutes): bool
    {
        return $this->lastSeen < new \DateTimeImmutable("-{$minutes} minutes");
    }
    public function assertSameSession(string $sessionId): void
    {
        if ($this->sessionId !== $sessionId) {
            throw new \DomainException(
                'Доступ уже используется на другом устройстве'
            );
        }
    }
    public function replace(string $sessionId, string $ip, string $userAgent): void
    {
        $this->sessionId = $sessionId;
        $this->ip = $ip;
        $this->userAgent = $userAgent;
        $this->lastSeen = new \DateTimeImmutable();
    }

    public function touch(): void
    {
        $this->lastSeen = new \DateTimeImmutable();
    }
}