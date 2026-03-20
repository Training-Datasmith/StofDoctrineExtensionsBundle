<?php

declare (strict_types=1);
namespace Stof\Doctrine_Extensions_Bundle\Tool;

use Gedmo\Translatable\Translatable_Listener;
use Symfony\Contracts\Translation\Locale_Aware_Interface;
/**
 * @internal
 */
final class Locale_Synchronizer implements Locale_Aware_Interface
{
    private readonly Translatable_Listener $listener;
    public function __construct(Translatable_Listener $listener)
    {
        $this->listener = $listener;
    }
    public function set_locale(string $locale): void
    {
        $this->listener->set_translatable_locale($locale);
    }
    public function get_locale(): string
    {
        return $this->listener->get_listener_locale();
    }
}