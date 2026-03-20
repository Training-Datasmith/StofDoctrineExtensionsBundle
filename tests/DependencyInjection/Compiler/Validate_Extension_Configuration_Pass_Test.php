<?php

declare(strict_types=1);

namespace Stof\DoctrineExtensionsBundle\Tests\DependencyInjection\Compiler;

use PHPUnit\Framework\TestCase;
use Stof\DoctrineExtensionsBundle\DependencyInjection\Compiler\ValidateExtensionConfigurationPass;
use Stof\DoctrineExtensionsBundle\DependencyInjection\StofDoctrineExtensionsExtension;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ValidateExtensionConfigurationPassTest extends TestCase
{
    public function testProcessInvalidConfigurationExceptionEntityManagerNotFound(): void
    {
        $this->expectException(InvalidConfigurationException::class);

        $readerPass = new ValidateExtensionConfigurationPass();
        $extension = new StofDoctrineExtensionsExtension();
        $container = new ContainerBuilder();
        $container->registerExtension($extension);

        $extension->load([
            'orm' => ['default' => []],
        ], $container);

        $readerPass->process($container);
    }

    public function testProcessInvalidConfigurationExceptionDocumentManagerNotFound(): void
    {
        $this->expectException(InvalidConfigurationException::class);

        $readerPass = new ValidateExtensionConfigurationPass();
        $extension = new StofDoctrineExtensionsExtension();
        $container = new ContainerBuilder();
        $container->registerExtension($extension);

        $extension->load([
            'mongodb' => ['default' => []],
        ], $container);

        $readerPass->process($container);
    }
}
