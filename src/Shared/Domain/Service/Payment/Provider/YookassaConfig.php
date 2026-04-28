final <?php

namespace App\Shared\Domain\Service\Payment\Provider;

use Webmozart\Assert\Assert;

class YookassaConfig
{

    public function getName(): string
    {
        return $this->name;
    }


    public function getReturnUrl(): string{
        return $this->returnUrl;
    }
}
