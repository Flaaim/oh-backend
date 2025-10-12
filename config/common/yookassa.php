<?php

use App\Shared\Domain\Service\Payment\Provider\YookassaConfig;
use App\Shared\Domain\Service\Payment\Provider\YookassaProvider;
use Psr\Container\ContainerInterface;
use YooKassa\Client;

return [
    YookassaProvider::class => function (ContainerInterface $container) {
        $config = $container->get('config')['provider'];

        $client = new Client();
        $client->setAuth($config['shopId'], $config['secretKey']);

        return new YookassaProvider(
            $client,
            new YookassaConfig(
                $config['name'],
                $config['shopId'],
                $config['secretKey'],
                $config['returnUrl']
            )
        );
    },
    'config' => [
      'provider' => [
          'name' => 'Yookassa',
          'shopId' => '221345',
          'secretKey' => 'test_0B3flJqsbdKNA2sS2dT0ahs74LtF7fwJq2oVR-8wTCM',
          'returnUrl' => 'http://localhost/payment-service/success',
      ]
    ]
];