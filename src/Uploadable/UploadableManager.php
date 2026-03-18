<?php

namespace Stof\DoctrineExtensionsBundle\Uploadable;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Gedmo\Uploadable\FileInfo\FileInfoInterface;
use Gedmo\Uploadable\UploadableListener;

class UploadableManager
{
    private readonly UploadableListener $listener;

    /**
     * @param class-string<FileInfoInterface> $fileInfoClass
     */
    public function __construct(UploadableListener $listener, private readonly string $fileInfoClass)
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
    public function markEntityToUpload($entity, $fileInfo): void
    {
        if (is_object($fileInfo) && $fileInfo instanceof UploadedFile) {
            $fileInfoClass = $this->fileInfoClass;

            $fileInfo = new $fileInfoClass($fileInfo);
        }

        $this->listener->addEntityFileInfo($entity, $fileInfo);
    }

    public function getUploadableListener(): \Gedmo\Uploadable\UploadableListener
    {
        return $this->listener;
    }
}
