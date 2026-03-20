<?php

declare (strict_types=1);
namespace Symfony\Component\Dependency_Injection\Loader\Configurator;

use Stof\Doctrine_Extensions_Bundle\Tool\Request_Stack_Ip_Address_Provider;
use Stof\Doctrine_Extensions_Bundle\Tool\Token_Storage_Actor_Provider;
return static function (Container_Configurator $container): void {
    $container->services()->set('stof_doctrine_extensions.tool.actor_provider', Token_Storage_Actor_Provider::class)->args([service('security.token_storage')->null_on_invalid(), service('security.authorization_checker')->null_on_invalid()])->set('stof_doctrine_extensions.tool.ip_address_provider', Request_Stack_Ip_Address_Provider::class)->args([service('request_stack')->null_on_invalid()]);
};