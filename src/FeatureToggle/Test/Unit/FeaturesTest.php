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
        $features = new Features([
            'FIRST' => true,
            'SECOND' => false,
        ]);

        $this->assertTrue($features->isEnabled('FIRST'));
        $this->assertFalse($features->isEnabled('SECOND'));
    }
}
