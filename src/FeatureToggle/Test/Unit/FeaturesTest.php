<?php

declare(strict_types=1);

namespace App\FeatureToggle\Test\Unit;

use App\FeatureToggle\Features;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversClass(Features::class)]
final class FeaturesTest extends TestCase
{
    public function testIsEnabled(): void
    {
        $features = new Features([
            'FIRST' => true,
            'SECOND' => false,
        ]);

        self::assertTrue($features->isEnabled('FIRST'));
        self::assertFalse($features->isEnabled('SECOND'));
    }
}
