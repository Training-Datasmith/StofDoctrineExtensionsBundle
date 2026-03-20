<?php

declare (strict_types=1);
namespace Stof\Doctrine_Extensions_Bundle\Dependency_Injection;

use Symfony\Component\Cache\Adapter\Array_Adapter;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\File_Locator;
use Symfony\Component\Config\Loader\Loader_Interface;
use Symfony\Component\Dependency_Injection\Alias;
use Symfony\Component\Dependency_Injection\Container_Builder;
use Symfony\Component\Dependency_Injection\Extension\Extension;
use Symfony\Component\Dependency_Injection\Loader\Php_File_Loader;
/**
 * @internal
 */
class Stof_Doctrine_Extensions_Extension extends Extension
{
    private const LISTENER_EVENTS = ['blameable' => ['prePersist', 'onFlush', 'loadClassMetadata'], 'ip_traceable' => ['prePersist', 'onFlush', 'loadClassMetadata'], 'loggable' => ['loadClassMetadata', 'onFlush', 'postPersist'], 'reference_integrity' => ['loadClassMetadata', 'preRemove'], 'sluggable' => ['prePersist', 'onFlush', 'loadClassMetadata'], 'softdeleteable' => ['loadClassMetadata', 'onFlush', 'postFlush'], 'sortable' => ['onFlush', 'loadClassMetadata', 'prePersist', 'postPersist', 'preUpdate', 'postRemove', 'postFlush'], 'timestampable' => ['prePersist', 'onFlush', 'loadClassMetadata'], 'translatable' => ['postLoad', 'postPersist', 'preFlush', 'onFlush', 'loadClassMetadata'], 'tree' => ['prePersist', 'preRemove', 'preUpdate', 'onFlush', 'loadClassMetadata', 'postPersist', 'postUpdate', 'postRemove'], 'uploadable' => ['loadClassMetadata', 'preFlush', 'onFlush', 'postFlush']];
    /** @var list<string> */
    private array $entity_managers = [];
    /** @var list<string> */
    private array $document_managers = [];
    public function load(array $configs, Container_Builder $container): void
    {
        $processor = new Processor();
        $configuration = new Configuration();
        $config = $processor->process_configuration($configuration, $configs);
        $loader = new Php_File_Loader($container, new File_Locator(__DIR__ . '/../Resources/config'));
        $loader->load('tool.php');
        $loaded = [];
        $this->entity_managers = $this->process_object_manager_configurations($config['orm'], $container, $loader, $loaded, 'doctrine.event_listener');
        $this->document_managers = $this->process_object_manager_configurations($config['mongodb'], $container, $loader, $loaded, 'doctrine_mongodb.odm.event_listener');
        $container->set_parameter('stof_doctrine_extensions.default_locale', $config['default_locale']);
        $container->set_parameter('stof_doctrine_extensions.translation_fallback', $config['translation_fallback']);
        $container->set_parameter('stof_doctrine_extensions.persist_default_translation', $config['persist_default_translation']);
        $container->set_parameter('stof_doctrine_extensions.skip_translation_on_load', $config['skip_translation_on_load']);
        // Register the softdeleteable configuration if the listener is used
        if (isset($loaded['softdeleteable'])) {
            $container->get_definition('stof_doctrine_extensions.listener.softdeleteable')->replace_argument(0, $config['softdeleteable']['handle_post_flush_event']);
        }
        // Register the uploadable configuration if the listener is used
        if (isset($loaded['uploadable'])) {
            $uploadable_config = $config['uploadable'];
            $container->set_parameter('stof_doctrine_extensions.default_file_path', $uploadable_config['default_file_path']);
            $container->set_parameter('stof_doctrine_extensions.uploadable.default_file_info.class', $uploadable_config['default_file_info_class']);
            $container->set_parameter('stof_doctrine_extensions.uploadable.validate_writable_directory', $uploadable_config['validate_writable_directory']);
            if ($uploadable_config['default_file_path']) {
                $container->get_definition('stof_doctrine_extensions.listener.uploadable')->add_method_call('setDefaultPath', [$uploadable_config['default_file_path']]);
            }
            if ($uploadable_config['mime_type_guesser_class']) {
                if (!class_exists($uploadable_config['mime_type_guesser_class'])) {
                    $msg = 'Class "%s" configured to use as the mime type guesser in the Uploadable extension does not exist.';
                    throw new \InvalidArgumentException(sprintf($msg, $uploadable_config['mime_type_guesser_class']));
                }
                $container->set_parameter('stof_doctrine_extensions.uploadable.mime_type_guesser.class', $uploadable_config['mime_type_guesser_class']);
            }
        }
        if (isset($config['metadata_cache_pool'])) {
            $container->set_alias('stof_doctrine_extensions.metadata_cache', new Alias($config['metadata_cache_pool'], false));
        } else {
            $container->register('stof_doctrine_extensions.metadata_cache', Array_Adapter::class)->set_public(false);
        }
        foreach ($config['class'] as $listener => $class) {
            $container->set_parameter(sprintf('stof_doctrine_extensions.listener.%s.class', $listener), $class);
        }
    }
    /**
     * @internal
     */
    public function config_validate(Container_Builder $container): void
    {
        foreach ($this->entity_managers as $name) {
            if (!$container->has_definition(sprintf('doctrine.dbal.%s_connection', $name))) {
                throw new \InvalidArgumentException(sprintf('Invalid %s config: DBAL connection "%s" not found', $this->get_alias(), $name));
            }
        }
        foreach ($this->document_managers as $name) {
            if (!$container->has_definition(sprintf('doctrine_mongodb.odm.%s_document_manager', $name))) {
                throw new \InvalidArgumentException(sprintf('Invalid %s config: document manager "%s" not found', $this->get_alias(), $name));
            }
        }
    }
    /**
     * @param array<string, array<string, bool>> $configs
     * @param array<string, true>                $loaded
     *
     * @return list<string>
     */
    private function process_object_manager_configurations(array $configs, Container_Builder $container, Loader_Interface $loader, array &$loaded, string $doctrine_listener_tag): array
    {
        $used_managers = [];
        $listener_priorities = ['translatable' => -10, 'loggable' => 5, 'uploadable' => -5];
        foreach ($configs as $name => $listeners) {
            foreach ($listeners as $ext => $enabled) {
                if (!$enabled) {
                    continue;
                }
                if (!isset($loaded[$ext])) {
                    $loader->load($ext . '.php');
                    $loaded[$ext] = true;
                }
                $attributes = ['connection' => $name];
                if (isset($listener_priorities[$ext])) {
                    $attributes['priority'] = $listener_priorities[$ext];
                }
                $definition = $container->get_definition(sprintf('stof_doctrine_extensions.listener.%s', $ext));
                foreach (self::LISTENER_EVENTS[$ext] as $event) {
                    $attributes['event'] = $event;
                    $definition->add_tag($doctrine_listener_tag, $attributes);
                }
                $used_managers[$name] = true;
            }
        }
        return array_keys($used_managers);
    }
}