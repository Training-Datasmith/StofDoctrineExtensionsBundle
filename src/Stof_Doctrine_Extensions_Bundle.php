<?php

declare (strict_types=1);
namespace Stof\Doctrine_Extensions_Bundle;

use Stof\Doctrine_Extensions_Bundle\Dependency_Injection\Compiler\Reader_Pass;
use Stof\Doctrine_Extensions_Bundle\Dependency_Injection\Compiler\Validate_Extension_Configuration_Pass;
use Symfony\Component\Dependency_Injection\Container_Builder;
use Symfony\Component\Http_Kernel\Bundle\Bundle;
class Stof_Doctrine_Extensions_Bundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(Container_Builder $container): void
    {
        $container->add_compiler_pass(new Validate_Extension_Configuration_Pass());
        $container->add_compiler_pass(new Reader_Pass());
    }
}