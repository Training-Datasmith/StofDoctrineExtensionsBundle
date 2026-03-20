<?php

declare (strict_types=1);
namespace Stof\Doctrine_Extensions_Bundle\Dependency_Injection\Compiler;

use Stof\Doctrine_Extensions_Bundle\Dependency_Injection\Stof_Doctrine_Extensions_Extension;
use Symfony\Component\Dependency_Injection\Compiler\Compiler_Pass_Interface;
use Symfony\Component\Dependency_Injection\Container_Builder;
/**
 * @internal
 */
class Validate_Extension_Configuration_Pass implements Compiler_Pass_Interface
{
    /**
     * Validate the DoctrineExtensions DIC extension config.
     *
     * This validation runs in a discrete compiler pass because it depends on
     * DBAL and ODM services, which aren't available during the config merge
     * compiler pass.
     *
     *
     */
    public function process(Container_Builder $container): void
    {
        $extension = $container->get_extension('stof_doctrine_extensions');
        \assert($extension instanceof Stof_Doctrine_Extensions_Extension);
        $extension->config_validate($container);
    }
}