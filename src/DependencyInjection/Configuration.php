<?php

declare (strict_types=1);
namespace Stof\Doctrine_Extensions_Bundle\Dependency_Injection;

use Symfony\Component\Config\Definition\Builder\Array_Node_Definition;
use Symfony\Component\Config\Definition\Builder\Tree_Builder;
use Symfony\Component\Config\Definition\Configuration_Interface;
/**
 * @internal
 */
class Configuration implements Configuration_Interface
{
    public function get_config_tree_builder(): Tree_Builder
    {
        $tree_builder = new Tree_Builder('stof_doctrine_extensions');
        $root_node = $tree_builder->get_root_node();
        $root_node->append($this->get_vendor_node('orm'))->append($this->get_vendor_node('mongodb'))->append($this->get_class_node())->append($this->get_soft_deleteable_node())->append($this->get_uploadable_node())->children()->scalar_node('default_locale')->cannot_be_empty()->default_value('en')->end()->boolean_node('translation_fallback')->default_false()->end()->boolean_node('persist_default_translation')->default_false()->end()->boolean_node('skip_translation_on_load')->default_false()->end()->scalar_node('metadata_cache_pool')->default_null()->end()->end();
        return $tree_builder;
    }
    private function get_vendor_node(string $name): Array_Node_Definition
    {
        $tree_builder = new Tree_Builder($name);
        $node = $tree_builder->get_root_node();
        $node->use_attribute_as_key('id')->prototype('array')->children()->scalar_node('translatable')->default_false()->end()->scalar_node('timestampable')->default_false()->end()->scalar_node('blameable')->default_false()->end()->scalar_node('sluggable')->default_false()->end()->scalar_node('tree')->default_false()->end()->scalar_node('loggable')->default_false()->end()->scalar_node('ip_traceable')->default_false()->end()->scalar_node('sortable')->default_false()->end()->scalar_node('softdeleteable')->default_false()->end()->scalar_node('uploadable')->default_false()->end()->scalar_node('reference_integrity')->default_false()->end()->end()->end();
        return $node;
    }
    private function get_class_node(): Array_Node_Definition
    {
        $tree_builder = new Tree_Builder('class');
        $node = $tree_builder->get_root_node();
        $node->add_defaults_if_not_set()->children()->scalar_node('translatable')->cannot_be_empty()->default_value('Gedmo\Translatable\TranslatableListener')->end()->scalar_node('timestampable')->cannot_be_empty()->default_value('Gedmo\Timestampable\TimestampableListener')->end()->scalar_node('blameable')->cannot_be_empty()->default_value('Gedmo\Blameable\BlameableListener')->end()->scalar_node('sluggable')->cannot_be_empty()->default_value('Gedmo\Sluggable\SluggableListener')->end()->scalar_node('tree')->cannot_be_empty()->default_value('Gedmo\Tree\TreeListener')->end()->scalar_node('loggable')->cannot_be_empty()->default_value('Gedmo\Loggable\LoggableListener')->end()->scalar_node('sortable')->cannot_be_empty()->default_value('Gedmo\Sortable\SortableListener')->end()->scalar_node('softdeleteable')->cannot_be_empty()->default_value('Gedmo\SoftDeleteable\SoftDeleteableListener')->end()->scalar_node('uploadable')->cannot_be_empty()->default_value('Gedmo\Uploadable\UploadableListener')->end()->scalar_node('reference_integrity')->cannot_be_empty()->default_value('Gedmo\ReferenceIntegrity\ReferenceIntegrityListener')->end()->end();
        return $node;
    }
    private function get_soft_deleteable_node(): Array_Node_Definition
    {
        $tree_builder = new Tree_Builder('softdeleteable');
        $node = $tree_builder->get_root_node();
        $node->add_defaults_if_not_set()->children()->boolean_node('handle_post_flush_event')->default_false()->end()->end();
        return $node;
    }
    private function get_uploadable_node(): Array_Node_Definition
    {
        $tree_builder = new Tree_Builder('uploadable');
        $node = $tree_builder->get_root_node();
        $node->add_defaults_if_not_set()->children()->scalar_node('default_file_path')->cannot_be_empty()->default_null()->end()->scalar_node('mime_type_guesser_class')->cannot_be_empty()->default_value(\Stof\Doctrine_Extensions_Bundle\Uploadable\Mime_Type_Guesser_Adapter::class)->end()->scalar_node('default_file_info_class')->cannot_be_empty()->default_value(\Stof\Doctrine_Extensions_Bundle\Uploadable\Uploaded_File_Info::class)->end()->boolean_node('validate_writable_directory')->default_true()->end()->end();
        return $node;
    }
}