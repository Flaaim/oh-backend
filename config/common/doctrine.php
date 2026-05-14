<?php

declare(strict_types=1);

use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\UnderscoreNamingStrategy;
use Doctrine\ORM\ORMSetup;
use Doctrine\ORM\Tools\Console\EntityManagerProvider;
use Doctrine\ORM\Tools\Console\EntityManagerProvider\SingleManagerProvider;
use Psr\Container\ContainerInterface;
use Symfony\Component\Messenger\Bridge\Doctrine\Transport\DoctrineTransport;
use Symfony\Component\Messenger\Bridge\Doctrine\Transport\Connection as MessengerDoctrineConnection;
use Symfony\Component\Messenger\Transport\Serialization\PhpSerializer;

return [
    EntityManagerInterface::class => function (ContainerInterface $container) {
        $settings = $container->get('config')['doctrine'];

        $config = ORMSetup::createAttributeMetadataConfiguration(
            $settings['metadata_dirs'],
            $settings['dev_mode'],
            $settings['proxy_dir'],
            null
        );

        $config->setNamingStrategy(new UnderscoreNamingStrategy());

        $config->setSchemaAssetsFilter(fn ($assetName) => !str_starts_with($assetName, 'doctrine_migration_versions'));

        foreach ($settings['types'] as $name => $class) {
            if (!Type::hasType($name)) {
                Type::addType($name, $class);
            }
        }

        $connection = DriverManager::getConnection(
            $settings['connection'],
            $config
        );

        return new EntityManager($connection, $config);
    },
    'messenger.transport.async' => function (ContainerInterface $container) {
        $dsn = getenv('MESSENGER_TRANSPORT_DSN');

        if (!$dsn) {
            return null;
        }

        $em = $container->get(EntityManagerInterface::class);
        $dbalConnection = $em->getConnection();

        $messengerConnection = new MessengerDoctrineConnection([
            'table_name' => 'messenger_messages',
        ], $dbalConnection);
        return new DoctrineTransport($messengerConnection, new PhpSerializer());
    },
    'config' => [
        'doctrine' => [
            'dev_mode' => false,
            'cache_dir' => __DIR__ . '/../../var/cache/doctrine/cache',
            'proxy_dir' => __DIR__ . '/../../var/cache/doctrine/proxy',
            'connection' => [
                'driver' => 'pdo_mysql',
                'host' => getenv('DB_HOST'),
                'user' => getenv('DB_USER'),
                'password' => getenv('DB_PASSWORD'),
                'dbname' => getenv('DB_NAME'),
                'charset' => 'utf8mb4',
                'driverOptions' => [
                    1002 => "SET NAMES 'utf8mb4'",
                ],
            ],
            'metadata_dirs' => [
                __DIR__ . '/../../src/Payment/Entity',
                __DIR__ . '/../../src/Product/Entity',
                __DIR__ . '/../../src/Access/Entity',
                __DIR__ . '/../../src/Distribution/Entity',
                __DIR__ . '/../../src/Recipient/Entity',
            ],
            'types' => [
                App\Shared\Domain\ValueObject\IdType::NAME => App\Shared\Domain\ValueObject\IdType::class,
                App\Shared\Domain\ValueObject\UpdatedAtType::NAME => App\Shared\Domain\ValueObject\UpdatedAtType::class,


                App\Product\Entity\PriceType::NAME => App\Product\Entity\PriceType::class,
                App\Product\Entity\FileType::NAME => App\Product\Entity\FileType::class,


                App\Payment\Entity\EmailType::NAME => App\Payment\Entity\EmailType::class,
                App\Payment\Entity\StatusType::NAME => App\Payment\Entity\StatusType::class,

                App\Access\Entity\EmailType::NAME => App\Access\Entity\EmailType::class,
                App\Access\Entity\AccessIdType::NAME => App\Access\Entity\AccessIdType::class,

                \App\Recipient\Entity\EmailType::NAME => \App\Recipient\Entity\EmailType::class,
                \App\Recipient\Entity\RecipientIdType::NAME => \App\Recipient\Entity\RecipientIdType::class,

                \App\Distribution\Entity\DistributionIdType::NAME => \App\Distribution\Entity\DistributionIdType::class,

            ],
        ],
    ],
    EntityManagerProvider::class => fn (ContainerInterface $container) => new SingleManagerProvider($container->get(EntityManagerInterface::class)),
];
