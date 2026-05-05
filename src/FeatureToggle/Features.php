<?php

namespace App\FeatureToggle;

use App\FeatureToggle\FeatureFlag;

class Features implements FeatureFlag
{
    #[\Override]
    public function isEnabled(string $name): bool
    {
        return false;
    }
}
