<?php

declare (strict_types=1);
namespace Symfony\Component\Dependency_Injection\Loader\Configurator;

use Gedmo\Blameable\Blameable_Listener;
use Stof\Doctrine_Extensions_Bundle\Event_Listener\Blame_Listener;
return static function (Container_Configurator $container): void {
    $container->parameters()->set('stof_doctrine_extensions.listener.blameable.class', Blameable_Listener::class)->set('stof_doctrine_extensions.event_listener.blame.class', Blame_Listener::class);
    $container->services()->set('stof_doctrine_extensions.listener.blameable', param('stof_doctrine_extensions.listener.blameable.class'))->call('setCacheItemPool', [service('stof_doctrine_extensions.metadata_cache')])->call('setAnnotationReader', [service('.stof_doctrine_extensions.reader')->ignore_on_invalid()])->call('setActorProvider', [service('stof_doctrine_extensions.tool.actor_provider')])->set('stof_doctrine_extensions.event_listener.blame', param('stof_doctrine_extensions.event_listener.blame.class'))->deprecate('stof/doctrine-extensions-bundle', '1.14', 'The "%service_id%" service is deprecated and will be removed in 2.0. The "stof_doctrine_extensions.tool.actor_provider" service should be used to provide the user instead.')->args([service('stof_doctrine_extensions.listener.blameable'), service('security.token_storage')->null_on_invalid(), service('security.authorization_checker')->null_on_invalid()]);
};