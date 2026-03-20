<?php

declare (strict_types=1);
namespace Stof\Doctrine_Extensions_Bundle\Event_Listener;

use Gedmo\Translatable\Translatable_Listener;
use Symfony\Component\Event_Dispatcher\Event_Subscriber_Interface;
use Symfony\Component\Http_Kernel\Event\Request_Event;
use Symfony\Component\Http_Kernel\Kernel_Events;
/**
 * This listener sets the current locale for the TranslatableListener
 *
 * @author Christophe COEVOET
 *
 * @deprecated since 1.14. Use the LocaleSynchronizer instead.
 */
class Locale_Listener implements Event_Subscriber_Interface
{
    private readonly Translatable_Listener $translatable_listener;
    public function __construct(Translatable_Listener $translatable_listener)
    {
        $this->translatable_listener = $translatable_listener;
    }
    /**
     * @internal
     */
    public function on_kernel_request(Request_Event $event): void
    {
        $this->translatable_listener->set_translatable_locale($event->get_request()->get_locale());
    }
    /**
     * @return array<string, string>
     */
    public static function get_subscribed_events(): array
    {
        return [Kernel_Events::REQUEST => 'onKernelRequest'];
    }
}