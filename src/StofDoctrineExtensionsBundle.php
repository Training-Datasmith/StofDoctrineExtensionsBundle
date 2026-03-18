<?php

declare(strict_types=1);

namespace Stof\DoctrineExtensionsBundle;

use Stof\DoctrineExtensionsBundle\DependencyInjection\Compiler\ReaderPass;
use Stof\DoctrineExtensionsBundle\DependencyInjection\Compiler\ValidateExtensionConfigurationPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class StofDoctrineExtensionsBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new ValidateExtensionConfigurationPass());
        $container->addCompilerPass(new ReaderPass());
    }
}
