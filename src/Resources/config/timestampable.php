<?php

declare (strict_types=1);
namespace Symfony\Component\Dependency_Injection\Loader\Configurator;

use Gedmo\Timestampable\Timestampable_Listener;
return static function (Container_Configurator $container): void {
    $container->parameters()->set('stof_doctrine_extensions.listener.timestampable.class', Timestampable_Listener::class);
    $container->services()->set('stof_doctrine_extensions.listener.timestampable', param('stof_doctrine_extensions.listener.timestampable.class'))->call('setCacheItemPool', [service('stof_doctrine_extensions.metadata_cache')])->call('setClock', [service('clock')->ignore_on_invalid()])->call('setAnnotationReader', [service('.stof_doctrine_extensions.reader')->ignore_on_invalid()]);
};