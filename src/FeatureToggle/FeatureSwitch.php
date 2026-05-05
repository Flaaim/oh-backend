<?php

namespace App\FeatureToggle;

interface FeatureSwitch
{
    public function enable(string $name): void;
}
