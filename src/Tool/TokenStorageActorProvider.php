<?php

namespace Stof\DoctrineExtensionsBundle\Tool;

use Gedmo\Tool\ActorProviderInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Provides an actor for the extensions using the token storage.
 *
 * @internal
 */
final class TokenStorageActorProvider implements ActorProviderInterface
{
    public function __construct(private readonly ?TokenStorageInterface $tokenStorage = null, private readonly ?AuthorizationCheckerInterface $authorizationChecker = null)
    {
    }

    public function getActor(): ?UserInterface
    {
        if (null === $this->tokenStorage || null === $this->authorizationChecker) {
            return null;
        }

        $token = $this->tokenStorage->getToken();

        if (null === $token || !$this->authorizationChecker->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return null;
        }

        return $token->getUser();
    }
}
