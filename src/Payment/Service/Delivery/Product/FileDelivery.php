<?php

declare(strict_types=1);

namespace App\Payment\Service\Delivery\Product;

use App\Payment\Entity\Email;
use App\Payment\Service\Delivery\ProductDeliveryInterface;
use App\Product\Entity\Product;
use App\Product\Entity\Type;
use App\Shared\Domain\ValueObject\RootPath;

class FileDelivery implements ProductDeliveryInterface
{
    public function __construct(
        private readonly FileSender $fileSender,
        private readonly RootPath $rootPath
    ) {
    }
    public function deliver(string $email, Product $product): void
    {
        $file = $product->getFile();
        $file->mergeRoot($this->rootPath);

        $subject = $product->getName();
        $pathToFile = $file->getFile();

        $this->fileSender->send(new Email($email), $subject, $pathToFile, 'mail/template_file.html.twig');
    }

    public function supports(string $type): bool
    {
        return Type::File->value === $type;
    }
}
