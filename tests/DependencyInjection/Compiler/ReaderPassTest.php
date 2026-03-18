<?php

declare(strict_types=1);

namespace Stof\DoctrineExtensionsBundle\Tests\DependencyInjection\Compiler;

use PHPUnit\Framework\TestCase;
use Stof\DoctrineExtensionsBundle\DependencyInjection\Compiler\ReaderPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ReaderPassTest extends TestCase
{
    public function testProcess(): void
    {
        $readerPass = new ReaderPass();
        $container = new ContainerBuilder();

        $readerPass->process($container);

        self::assertTrue($container->has('.stof_doctrine_extensions.reader'));
    }
}
