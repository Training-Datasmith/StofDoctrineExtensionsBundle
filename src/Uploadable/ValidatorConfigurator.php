<?php

declare (strict_types=1);
namespace Stof\Doctrine_Extensions_Bundle\Uploadable;

use Gedmo\Uploadable\Mapping\Validator;
/**
 * @internal
 */
class Validator_Configurator
{
    public function __construct(private bool $validate_writable_directory)
    {
    }
    public function configure(): void
    {
        Validator::$validate_writable_directory = $this->validate_writable_directory;
    }
}