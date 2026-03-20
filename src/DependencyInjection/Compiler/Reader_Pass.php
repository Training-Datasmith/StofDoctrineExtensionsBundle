<?php

declare (strict_types=1);
namespace Stof\Doctrine_Extensions_Bundle\Dependency_Injection\Compiler;

use Gedmo\Mapping\Driver\Attribute_Reader;
use Symfony\Component\Dependency_Injection\Alias;
use Symfony\Component\Dependency_Injection\Compiler\Compiler_Pass_Interface;
use Symfony\Component\Dependency_Injection\Container_Builder;
/**
 * @internal
 */
final class Reader_Pass implements Compiler_Pass_Interface
{
    public function process(Container_Builder $container): void
    {
        if ($container->has('annotation_reader')) {
            $container->set_alias('.stof_doctrine_extensions.reader', new Alias('annotation_reader', false));
            return;
        }
        $container->register('.stof_doctrine_extensions.reader', Attribute_Reader::class);
    }
}