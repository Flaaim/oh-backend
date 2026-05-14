<?php

declare(strict_types=1);

namespace App\FeatureToggle\Test\Unit;

use App\FeatureToggle\FeatureFlag;
use App\FeatureToggle\FeatureFlagTwigExtension;
use PHPUnit\Framework\TestCase;
use Twig\Environment;
use Twig\Loader\ArrayLoader;

class FeatureFlagTwigExtensionTest extends TestCase
{
    public function testActive(): void
    {
        $extension = new FeatureFlagTwigExtension($flag = $this->createMock(FeatureFlag::class));

        $flag->expects($this->once())->method('isEnabled')
            ->with($this->equalTo('NEW'))
            ->willReturn(true);

        $twig = new Environment(new ArrayLoader([
            'page.html.twig' => '<p>{{ is_feature_enabled(\'NEW\') ? \'true\' : \'false\'}}</p>',
        ]));
        $twig->addExtension($extension);

        $this->assertEquals('<p>true</p>', $twig->render('page.html.twig'));
    }

    public function testInActive(): void
    {
        $extension = new FeatureFlagTwigExtension($flag = $this->createMock(FeatureFlag::class));
        $flag->expects($this->once())->method('isEnabled')
            ->with($this->equalTo('NEW'))
            ->willReturn(false);

        $twig = new Environment(new ArrayLoader([
            'page.html.twig' => '<p>{{ is_feature_enabled(\'NEW\') ? \'true\' : \'false\'}}</p>',
        ]));

        $twig->addExtension($extension);
        $this->assertEquals('<p>false</p>', $twig->render('page.html.twig'));
    }
}
