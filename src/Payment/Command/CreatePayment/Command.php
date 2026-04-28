final <?php

namespace App\Payment\Command\CreatePayment;

use App\Product\Entity\Type;
use Symfony\Component\Validator\Constraints as Assert;

class Command
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Email]
        public string $email,
        #[Assert\NotBlank]
        #[Assert\Uuid]
        public string $productId,
        #[Assert\Choice(choices: ['file', 'access'])]
        public string $type,
    )
    {}
}