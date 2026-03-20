<?php

declare (strict_types=1);
namespace Stof\Doctrine_Extensions_Bundle\Uploadable;

use Gedmo\Uploadable\File_Info\File_Info_Interface;
use Gedmo\Uploadable\Uploadable_Listener;
use Symfony\Component\Http_Foundation\File\Uploaded_File;
class Uploadable_Manager
{
    private readonly Uploadable_Listener $listener;
    /**
     * @param class-string<FileInfoInterface> $fileInfoClass
     */
    public function __construct(Uploadable_Listener $listener, private readonly string $file_info_class)
    {
        $this->listener = $listener;
    }
    /**
     * This method marks an entity to be uploaded as soon as the "flush" method of your object manager is called.
     * After calling this method, the file info you passed is set for this entity in the listener. This is all it takes
     * to upload a file for an entity in the Uploadable extension.
     *
     * @param object $entity   - The entity you are marking to "Upload" as soon as you call "flush".
     * @param mixed  $fileInfo - The file info object or array. In Symfony, this will be typically an UploadedFile instance.
     */
    public function mark_entity_to_upload($entity, $file_info): void
    {
        if (is_object($file_info) && $file_info instanceof Uploaded_File) {
            $file_info_class = $this->file_info_class;
            $file_info = new $file_info_class($file_info);
        }
        $this->listener->add_entity_file_info($entity, $file_info);
    }
    public function get_uploadable_listener(): \Gedmo\Uploadable\Uploadable_Listener
    {
        return $this->listener;
    }
}