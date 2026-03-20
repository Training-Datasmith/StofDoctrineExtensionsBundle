<?php

declare (strict_types=1);
namespace Symfony\Component\Dependency_Injection\Loader\Configurator;

use Gedmo\Loggable\Loggable_Listener;
use Stof\Doctrine_Extensions_Bundle\Event_Listener\Logger_Listener;
return static function (Container_Configurator $container): void {
    $container->parameters()->set('stof_doctrine_extensions.listener.loggable.class', Loggable_Listener::class)->set('stof_doctrine_extensions.event_listener.logger.class', Logger_Listener::class);
    $container->services()->set('stof_doctrine_extensions.listener.loggable', param('stof_doctrine_extensions.listener.loggable.class'))->call('setCacheItemPool', [service('stof_doctrine_extensions.metadata_cache')])->call('setAnnotationReader', [service('.stof_doctrine_extensions.reader')->ignore_on_invalid()])->call('setActorProvider', [service('stof_doctrine_extensions.tool.actor_provider')])->set('stof_doctrine_extensions.event_listener.logger', param('stof_doctrine_extensions.event_listener.logger.class'))->deprecate('stof/doctrine-extensions-bundle', '1.14', 'The "%service_id%" service is deprecated and will be removed in 2.0. The "stof_doctrine_extensions.tool.actor_provider" service should be used to provide the user instead.')->args([service('stof_doctrine_extensions.listener.loggable'), service('security.token_storage')->null_on_invalid(), service('security.authorization_checker')->null_on_invalid()]);
};