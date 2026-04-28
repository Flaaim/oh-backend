final <?php

namespace App\Payment\Command\GetPaymentResult;

class Response implements \JsonSerializable
{
     public function __construct(
         public string $returnToken,
         public string $status,
         public string $email
     )
     {}

    #[\Override]
    public function jsonSerialize(): array
    {
        return [
            'returnToken' => $this->returnToken,
            'status' => $this->status,
            'email' => $this->email,
        ];
    }
}