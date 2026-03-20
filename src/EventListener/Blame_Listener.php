<?php

declare (strict_types=1);
namespace Stof\Doctrine_Extensions_Bundle\Event_Listener;

use Gedmo\Blameable\Blameable_Listener;
use Symfony\Component\Event_Dispatcher\Event_Subscriber_Interface;
use Symfony\Component\Http_Kernel\Event\Request_Event;
use Symfony\Component\Http_Kernel\Kernel_Events;
use Symfony\Component\Security\Core\Authentication\Token\Storage\Token_Storage_Interface;
use Symfony\Component\Security\Core\Authorization\Authorization_Checker_Interface;
/**
 * Sets the username from the security context by listening on kernel.request.
 *
 * On each main request, reads the current security token and passes the
 * authenticated user object to the Gedmo Blameable listener so that
 * blameable fields (createdBy, updatedBy) are populated correctly.
 *
 * @author David Buchmann <mail@davidbu.ch>
 *
 * @deprecated since 1.x — Use the actor provider approach instead; this listener will be removed in 2.0
 */
class Blame_Listener implements Event_Subscriber_Interface
{
    private readonly Blameable_Listener $blameable_listener;

    /**
     * @param Blameable_Listener                $blameable_listener   The Gedmo blameable Doctrine event listener
     * @param Token_Storage_Interface|null      $token_storage        Security token storage, or null when security is not configured
     * @param Authorization_Checker_Interface|null $authorization_checker Used to check IS_AUTHENTICATED_REMEMBERED
     *
     * @deprecated since 1.x — use the actor provider approach instead
     */
    public function __construct(
        Blameable_Listener $blameable_listener,
        private readonly ?Token_Storage_Interface $token_storage = null,
        private readonly ?Authorization_Checker_Interface $authorization_checker = null,
    ) {
        $this->blameable_listener = $blameable_listener;
    }

    /**
     * Injects the authenticated user into the Blameable listener on each main request.
     *
     * Skips sub-requests and requests without a valid security token to avoid
     * overwriting the blame user from a properly authenticated main request.
     *
     * @internal
     *
     * @param Request_Event $event The kernel request event (main request only)
     *
     * @return void
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
            $this->blameable_listener->set_user_value($token->get_user());
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