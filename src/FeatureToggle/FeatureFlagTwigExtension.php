<?php

namespace App\FeatureToggle;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class FeatureFlagTwigExtension extends AbstractExtension
{
    public function __construct(
        private readonly FeatureFlag $featureFlag
    ) {
    }
    #[\Override]
    public function getFunctions(): array
    {
        return [
            new TwigFunction('is_feature_enabled', [$this, 'isFeatureEnabled'])
        ];
    }

    public function isFeatureEnabled(string $name): bool
    {
        return $this->featureFlag->isEnabled($name);
    }
}
