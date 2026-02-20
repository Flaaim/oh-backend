<?php

namespace App\Payment\Test\Service\Product;

use App\Payment\Entity\Email;
use App\Payment\Service\Delivery\Product\FileDelivery;
use App\Payment\Service\Delivery\Product\FileSender;
use App\Product\Entity\File;
use App\Product\Entity\Type;
use App\Shared\Domain\Service\Template\RootPath;
use PHPUnit\Framework\TestCase;
use Test\Functional\Payment\ProductBuilder;

class FileDeliveryTest extends TestCase
{
    public function testSupports(): void
    {
        $fileDelivery = new FileDelivery(
            $this->createMock(FileSender::class),
            new RootPath(sys_get_temp_dir()),
        );


        self::assertTrue($fileDelivery->supports(Type::File->value));
    }

    public function testDeliver(): void
    {
        $email = new Email('some@email.ru');
        $file = $this->getFile();
        $product = (new ProductBuilder())->withFile($file)->build();
        $template = 'mail/template_file.html.twig';

        $delivery = new FileDelivery(
            $sender = $this->createMock(FileSender::class),
            new RootPath(sys_get_temp_dir()),
        );

        $sender->expects($this->once())->method('send')->with(
            $this->equalTo($email),
            $this->equalTo($product->getName()),
            $this->callback(function($path) use ($file) {
                return str_contains($path, $file->getValue());
            }),
            $this->equalTo($template)
        );

        $delivery->deliver($email->getValue(), $product);
    }
    private function getFile(): File
    {
        $tempFile = tempnam(sys_get_temp_dir(), 'template');
        return new File(basename($tempFile));
    }
}