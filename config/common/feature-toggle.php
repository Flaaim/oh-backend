<?php

declare(strict_types=1);

use App\FeatureToggle\FeatureFlag;
use App\FeatureToggle\Features;
use App\FeatureToggle\FeatureSwitch;
use Psr\Container\ContainerInterface;

return [
    FeatureFlag::class => DI\get(Features::class),
    FeatureSwitch::class => DI\get(FeatureSwitch::class),
    Features::class => function (ContainerInterface $container): FeatureFlag {

        $config = $container->get('config')['feature-toggle'];
        /**
         * @psalm-var array{features:array<string,bool>} $config
         */
        return new Features($config['features']);
    },
    'config' => [
        'feature-toggle' => [
            'features' => [
                'NEW_HOME' => false,
            ],
        ],
    ],
];
