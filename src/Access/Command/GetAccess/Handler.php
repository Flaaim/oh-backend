<?php

namespace App\Access\Command\GetAccess;

use App\Access\Entity\Access;
use App\Access\Entity\AccessRepository;
use App\Access\Entity\DTO\GetAccessDTO;
use App\Access\Service\UuidConverter;
use App\Shared\Domain\ProductQuery\ProductQueryInterface;
use App\Shared\Domain\ValueObject\RootPath;



class Handler
{
    public function __construct(
        private readonly AccessRepository $accesses,
        private readonly UuidConverter $uuidConverter,
        private readonly ProductQueryInterface $productQuery,
        private readonly RootPath $rootPath,
    ){
    }

    public function handle(Command $command): GetAccessDTO
    {
        $token = $this->uuidConverter->decode($command->encodedToken);

        $access = $this->accesses->getByToken($token);
        /** @var Access $access */
        if($access->isExpired()){
            throw new \DomainException('Срок действия доступа к файлу истек...');
        }

        $product = $this->productQuery->getProduct($access->getProductId());

        $pathToFile =  $this->rootPath->getValue() . DIRECTORY_SEPARATOR . $product->file;

        if(!file_exists($pathToFile)){
            throw new \DomainException('Файл не найден...');
        }

        $encodedProductId = $this->uuidConverter->encode($access->getProductId());

        return new GetAccessDTO(
            $encodedProductId,
            $access->getName(),
            $access->getCipher(),
            $access->getToken()->getExpired()->format('Y-m-d'),
            $access->getEmail()->getValue(),
        );
    }
}