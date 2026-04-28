final <?php

namespace App\Payment\Test\Service\Access;

use App\Access\Command\OpenAccess\Command;
use App\Access\Command\OpenAccess\Handler;
use App\Access\Entity\DTO\OpenAccessDTO;
use App\Payment\Entity\Email;
use App\Payment\Service\Delivery\Access\AccessDelivery;
use App\Payment\Service\Delivery\Access\AccessSender;
use App\Product\Entity\Type;
use PHPUnit\Framework\TestCase;
use Test\Functional\Payment\ProductBuilder;

class AccessDeliveryTest extends TestCase
{
    public function testSupport(): void
    {
        $delivery = new AccessDelivery(
            $this->createMock(Handler::class),
            $this->createMock(AccessSender::class),
        );

        self::assertTrue($delivery->supports(Type::Access->value));
    }

    public function testDeliver(): void
    {
        $email = new Email('some@email.ru');
        $product = (new ProductBuilder())->build();
        $command = new Command($email->getValue(), $product->getId()->getValue());
        $template = 'mail/template_access.html.twig';


        $delivery = new AccessDelivery(
            $handler = $this->createMock(Handler::class),
            $sender = $this->createMock(AccessSender::class),
        );

        $handler->expects($this->once())->method('handle')->with(
            $this->equalTo($command),
        )->willReturn($dto = new OpenAccessDTO(
            'some_url',
            $product->getName(),
            $product->getCipher(),
        ));

        $sender->expects($this->once())->method('send')->with(
            $this->equalTo($email),
            $this->equalTo($dto),
            $this->equalTo($template)
        );

        $delivery->deliver($email->getValue(), $product);
    }
}