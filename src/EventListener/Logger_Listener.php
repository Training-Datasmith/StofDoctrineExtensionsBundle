<?php

declare (strict_types=1);
namespace Stof\Doctrine_Extensions_Bundle\Event_Listener;

use Gedmo\Loggable\Loggable;
use Gedmo\Loggable\Loggable_Listener;
use Symfony\Component\Event_Dispatcher\Event_Subscriber_Interface;
use Symfony\Component\Http_Kernel\Event\Request_Event;
use Symfony\Component\Http_Kernel\Kernel_Events;
use Symfony\Component\Security\Core\Authentication\Token\Storage\Token_Storage_Interface;
use Symfony\Component\Security\Core\Authorization\Authorization_Checker_Interface;
/**
 * Sets the username from the security context by listening on kernel.request
 *
 * @author Christophe Coevoet <stof@notk.org>
 *
 * @deprecated to be removed in 2.0, use the actor provider instead
 *
 * @phpstan-template T of Loggable|object
 */
class Logger_Listener implements Event_Subscriber_Interface
{
    /** @var LoggableListener<T> */
    private readonly Loggable_Listener $loggable_listener;
    /**
     * @param LoggableListener<T> $loggableListener
     */
    public function __construct(Loggable_Listener $loggable_listener, private readonly ?Token_Storage_Interface $token_storage = null, private readonly ?Authorization_Checker_Interface $authorization_checker = null)
    {
        $this->loggable_listener = $loggable_listener;
    }
    /**
     * @internal
     */
    public function on_kernel_request(Request_Event $event): void
    {
        if (!$event->is_main_request()) {
            return;
        }
        if (null === $this->token_storage || null === $this->authorization_checker) {
            return;
        }
        $token = $this->token_storage->get_token();
        if (null !== $token && $this->authorization_checker->is_granted('IS_AUTHENTICATED_REMEMBERED')) {
            $this->loggable_listener->set_username($token);
        }
    }
    /**
     * @return array<string, string>
     */
    public static function get_subscribed_events(): array
    {
        return [Kernel_Events::REQUEST => 'onKernelRequest'];
    }
}