<?php

namespace App\FeatureToggle;

interface FeatureFlag
{
    public function isEnabled(string $name): bool;
}
