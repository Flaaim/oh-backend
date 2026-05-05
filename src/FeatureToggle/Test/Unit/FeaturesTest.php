<?php

namespace App\FeatureToggle\Test\Unit;

use App\FeatureToggle\Features;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Features::class)]
class FeaturesTest extends TestCase
{
    public function testIsEnabled(): void
    {
        $features = new Features();

        $this->assertFalse($features->isEnabled('feature'));
    }
}
