<?php

namespace App\Access\Command\OpenAccess;

use App\Access\Entity\Access;
use App\Access\Entity\AccessId;
use App\Access\Entity\AccessRepository;
use App\Access\Entity\DTO\OpenAccessDTO;
use App\Access\Entity\Email;
use App\Access\Entity\Token;
use App\Access\Service\UuidConverter;
use App\Flusher;
use App\Shared\Domain\ProductQuery\ProductQueryInterface;
use App\Shared\Domain\ValueObject\BaseUrl;
use Ramsey\Uuid\Uuid;

class Handler
{
    public function __construct(
        private readonly ProductQueryInterface $productQuery,
        private readonly AccessRepository $accesses,
        private readonly Flusher $flusher,
        private readonly BaseUrl $baseUrl,
        private readonly UuidConverter $uuidConverter,
    ){
    }

    public function handle(Command $command): OpenAccessDTO
    {
        $token = new Token(Uuid::uuid4()->toString(), new \DateTimeImmutable('+3 days'));

        $product = $this->productQuery->getProduct($command->productId);
        $access = new Access(
            AccessId::generate(),
            $product->name,
            $product->cipher,
            new Email($command->email),
            $product->id,
            $token
        );

        $this->accesses->create($access);

        $this->flusher->flush();

        $url = $this->baseUrl->getValue() .'?'. http_build_query([
            'token' => $this->uuidConverter->encode($access->getToken()->getValue())
            ]);

        return new OpenAccessDTO(
            $url,
            $access->getName(),
            $access->getCipher(),
        );
    }
}