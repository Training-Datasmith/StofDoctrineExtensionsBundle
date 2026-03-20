<?php

declare (strict_types=1);
namespace Symfony\Component\Dependency_Injection\Loader\Configurator;

use Gedmo\Reference_Integrity\Reference_Integrity_Listener;
return static function (Container_Configurator $container): void {
    $container->parameters()->set('stof_doctrine_extensions.listener.reference_integrity.class', Reference_Integrity_Listener::class);
    $container->services()->set('stof_doctrine_extensions.listener.reference_integrity', param('stof_doctrine_extensions.listener.reference_integrity.class'))->call('setCacheItemPool', [service('stof_doctrine_extensions.metadata_cache')])->call('setAnnotationReader', [service('.stof_doctrine_extensions.reader')->ignore_on_invalid()]);
};