<?php

declare(strict_types=1);

namespace Stof\DoctrineExtensionsBundle\Tests\DependencyInjection;

use Doctrine\Common\EventSubscriber;
use PHPUnit\Framework\TestCase;
use Stof\DoctrineExtensionsBundle\DependencyInjection\StofDoctrineExtensionsExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class StofDoctrineExtensionsExtensionTest extends TestCase
{
    /**
     * @return iterable<array{string}>
     */
    public static function provideExtensions()
    {
        return [
            ['blameable'],
            ['ip_traceable'],
            ['loggable'],
            ['reference_integrity'],
            ['sluggable'],
            ['softdeleteable'],
            ['sortable'],
            ['timestampable'],
            ['translatable'],
            ['tree'],
            ['uploadable'],
        ];
    }

    /**
     * @dataProvider provideExtensions
     */
    public function testLoadORMConfig(string $listener): void
    {
        $extension = new StofDoctrineExtensionsExtension();
        $container = new ContainerBuilder();

        $config = ['orm' => [
            'default' => [$listener => true],
            'other' => [$listener => true],
        ]];

        $extension->load([$config], $container);

        self::assertTrue($container->hasDefinition('stof_doctrine_extensions.listener.'.$listener));

        $def = $container->getDefinition('stof_doctrine_extensions.listener.'.$listener);

        self::assertTrue($def->hasTag('doctrine.event_listener'));

        $tags = $def->getTag('doctrine.event_listener');
        $configuredManagers = array_unique(array_column($tags, 'connection'));

        self::assertCount(2, $configuredManagers);
    }

    /**
     * @dataProvider provideExtensions
     */
    public function testLoadMongodbConfig(string $listener): void
    {
        $extension = new StofDoctrineExtensionsExtension();
        $container = new ContainerBuilder();

        $config = ['mongodb' => [
            'default' => [$listener => true],
            'other' => [$listener => true],
        ]];

        $extension->load([$config], $container);

        self::assertTrue($container->hasDefinition('stof_doctrine_extensions.listener.'.$listener));

        $def = $container->getDefinition('stof_doctrine_extensions.listener.'.$listener);

        self::assertTrue($def->hasTag('doctrine_mongodb.odm.event_listener'));

        $tags = $def->getTag('doctrine_mongodb.odm.event_listener');
        $configuredManagers = array_unique(array_column($tags, 'connection'));

        self::assertCount(2, $configuredManagers);
    }

    /**
     * @dataProvider provideExtensions
     */
    public function testLoadBothConfig(string $listener): void
    {
        $extension = new StofDoctrineExtensionsExtension();
        $container = new ContainerBuilder();

        $config = [
            'orm' => ['default' => [$listener => true]],
            'mongodb' => ['default' => [$listener => true]],
        ];

        $extension->load([$config], $container);

        self::assertTrue($container->hasDefinition('stof_doctrine_extensions.listener.'.$listener));

        $def = $container->getDefinition('stof_doctrine_extensions.listener.'.$listener);

        self::assertTrue($def->hasTag('doctrine.event_listener'));
        self::assertTrue($def->hasTag('doctrine_mongodb.odm.event_listener'));

        self::assertCount(1, array_unique(array_column($def->getTag('doctrine.event_listener'), 'connection')));
        self::assertCount(1, array_unique(array_column($def->getTag('doctrine_mongodb.odm.event_listener'), 'connection')));
    }

    /**
     * @dataProvider provideExtensions
     */
    public function testEventConsistency(string $listener): void
    {
        $extension = new StofDoctrineExtensionsExtension();
        $container = new ContainerBuilder();

        $config = ['orm' => [
            'default' => [$listener => true],
        ]];

        $extension->load([$config], $container);

        $def = $container->getDefinition('stof_doctrine_extensions.listener.'.$listener);
        $configuredEvents = array_column($def->getTag('doctrine.event_listener'), 'event');

        $listenerInstance = $container->get('stof_doctrine_extensions.listener.'.$listener);

        if (!$listenerInstance instanceof EventSubscriber) {
            self::markTestSkipped(sprintf('The listener for "%s" is not a Doctrine event subscriber.', $listener));
        }

        self::assertEqualsCanonicalizing($listenerInstance->getSubscribedEvents(), $configuredEvents);
    }
}
