<?php

declare(strict_types=1);

namespace Stof\DoctrineExtensionsBundle\EventListener;

use Gedmo\Blameable\BlameableListener;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Sets the username from the security context by listening on kernel.request
 *
 * @author David Buchmann <mail@davidbu.ch>
 *
 * @deprecated to be removed in 2.0, use the actor provider instead
 */
class BlameListener implements EventSubscriberInterface
{
    private readonly BlameableListener $blameableListener;

    public function __construct(BlameableListener $blameableListener, private readonly ?TokenStorageInterface $tokenStorage = null, private readonly ?AuthorizationCheckerInterface $authorizationChecker = null)
    {
        $this->blameableListener = $blameableListener;
    }

    /**
     * @internal
     */
    public function onKernelRequest(RequestEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        if (null === $this->tokenStorage || null === $this->authorizationChecker) {
            return;
        }

        $token = $this->tokenStorage->getToken();
        if (null !== $token && $this->authorizationChecker->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $this->blameableListener->setUserValue($token->getUser());
        }
    }

    /**
     * @return array<string, string>
     */
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => 'onKernelRequest',
        ];
    }
}
