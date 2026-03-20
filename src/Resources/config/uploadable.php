<?php

declare (strict_types=1);
namespace Symfony\Component\Dependency_Injection\Loader\Configurator;

use Gedmo\Uploadable\Uploadable_Listener;
use Stof\Doctrine_Extensions_Bundle\Uploadable\Mime_Type_Guesser_Adapter;
use Stof\Doctrine_Extensions_Bundle\Uploadable\Uploadable_Manager;
use Stof\Doctrine_Extensions_Bundle\Uploadable\Uploaded_File_Info;
use Stof\Doctrine_Extensions_Bundle\Uploadable\Validator_Configurator;
return static function (Container_Configurator $container): void {
    $container->parameters()->set('stof_doctrine_extensions.listener.uploadable.class', Uploadable_Listener::class)->set('stof_doctrine_extensions.uploadable.manager.class', Uploadable_Manager::class)->set('stof_doctrine_extensions.uploadable.mime_type_guesser.class', Mime_Type_Guesser_Adapter::class)->set('stof_doctrine_extensions.uploadable.default_file_info.class', Uploaded_File_Info::class);
    $container->services()->set('stof_doctrine_extensions.listener.uploadable', param('stof_doctrine_extensions.listener.uploadable.class'))->configurator([service('stof_doctrine_extensions.uploadable.configurator'), 'configure'])->args([service('stof_doctrine_extensions.uploadable.mime_type_guesser')])->call('setCacheItemPool', [service('stof_doctrine_extensions.metadata_cache')])->call('setAnnotationReader', [service('.stof_doctrine_extensions.reader')->ignore_on_invalid()])->call('setDefaultFileInfoClass', [param('stof_doctrine_extensions.uploadable.default_file_info.class')])->set('stof_doctrine_extensions.uploadable.mime_type_guesser', param('stof_doctrine_extensions.uploadable.mime_type_guesser.class'))->set('stof_doctrine_extensions.uploadable.manager', param('stof_doctrine_extensions.uploadable.manager.class'))->args([service('stof_doctrine_extensions.listener.uploadable'), param('stof_doctrine_extensions.uploadable.default_file_info.class')])->alias(Uploadable_Manager::class, 'stof_doctrine_extensions.uploadable.manager')->set('stof_doctrine_extensions.uploadable.configurator', Validator_Configurator::class)->args([param('stof_doctrine_extensions.uploadable.validate_writable_directory')]);
};