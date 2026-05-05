<?php

namespace App\FeatureToggle;

class Features implements FeatureFlag
{
    public function __construct(
        private readonly array $features
    ) {
    }
    #[\Override]
    public function isEnabled(string $name): bool
    {
        if (!array_key_exists($name, $this->features)) {
            return false;
        }
        return $this->features[$name];
    }
}
