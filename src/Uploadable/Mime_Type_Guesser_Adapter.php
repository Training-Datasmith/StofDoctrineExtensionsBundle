<?php

declare (strict_types=1);
namespace Stof\Doctrine_Extensions_Bundle\Uploadable;

use Gedmo\Uploadable\Mime_Type\Mime_Type_Guesser_Interface;
use Symfony\Component\Mime\Mime_Types;
class Mime_Type_Guesser_Adapter implements Mime_Type_Guesser_Interface
{
    /**
     * @param string $filePath
     * @return ?string
     */
    public function guess($file_path)
    {
        return Mime_Types::get_default()->guess_mime_type($file_path);
    }
}