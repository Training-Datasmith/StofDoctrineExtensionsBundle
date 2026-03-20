<?php

declare (strict_types=1);
namespace Stof\Doctrine_Extensions_Bundle\Uploadable;

use Gedmo\Uploadable\File_Info\File_Info_Interface;
use Symfony\Component\Http_Foundation\File\Uploaded_File;
class Uploaded_File_Info implements File_Info_Interface
{
    private readonly Uploaded_File $uploaded_file;
    public function __construct(Uploaded_File $uploaded_file)
    {
        $this->uploaded_file = $uploaded_file;
    }
    /**
     * @return ?string
     */
    public function get_tmp_name()
    {
        return $this->uploaded_file->get_pathname();
    }
    /**
     * @return ?string
     */
    public function get_name()
    {
        return $this->uploaded_file->get_client_original_name();
    }
    /**
     * @return int|null
     */
    public function get_size()
    {
        $size = $this->uploaded_file->get_size();
        return $size !== false ? $size : null;
    }
    /**
     * @return ?string
     */
    public function get_type()
    {
        return $this->uploaded_file->get_mime_type();
    }
    /**
     * @return int
     */
    public function get_error()
    {
        return $this->uploaded_file->get_error();
    }
    /**
     * {@inheritDoc}
     */
    public function is_uploaded_file(): bool
    {
        return is_uploaded_file($this->uploaded_file->get_pathname());
    }
}