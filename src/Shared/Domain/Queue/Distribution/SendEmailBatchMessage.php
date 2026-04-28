final <?php

namespace App\Shared\Domain\Queue\Distribution;

class SendEmailBatchMessage
{
    public function __construct(
        public string $distributionId,
    ){
    }


}