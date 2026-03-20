<?php

declare (strict_types=1);
namespace Symfony\Component\Dependency_Injection\Loader\Configurator;

use Gedmo\Ip_Traceable\Ip_Traceable_Listener;
return static function (Container_Configurator $container): void {
    $container->services()->set('stof_doctrine_extensions.listener.ip_traceable', Ip_Traceable_Listener::class)->call('setCacheItemPool', [service('stof_doctrine_extensions.metadata_cache')])->call('setAnnotationReader', [service('.stof_doctrine_extensions.reader')->ignore_on_invalid()])->call('setIpAddressProvider', [service('stof_doctrine_extensions.tool.ip_address_provider')]);
};