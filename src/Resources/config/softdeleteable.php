<?php

declare (strict_types=1);
namespace Symfony\Component\Dependency_Injection\Loader\Configurator;

use Gedmo\Soft_Deleteable\Soft_Deleteable_Listener;
return static function (Container_Configurator $container): void {
    $container->parameters()->set('stof_doctrine_extensions.listener.softdeleteable.class', Soft_Deleteable_Listener::class);
    $container->services()->set('stof_doctrine_extensions.listener.softdeleteable', param('stof_doctrine_extensions.listener.softdeleteable.class'))->args([abstract_arg('Set in the extension')])->call('setCacheItemPool', [service('stof_doctrine_extensions.metadata_cache')])->call('setClock', [service('clock')->ignore_on_invalid()])->call('setAnnotationReader', [service('.stof_doctrine_extensions.reader')->ignore_on_invalid()]);
};