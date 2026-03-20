<?php

declare (strict_types=1);
namespace Stof\Doctrine_Extensions_Bundle\Tool;

use Gedmo\Tool\Actor_Provider_Interface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\Token_Storage_Interface;
use Symfony\Component\Security\Core\Authorization\Authorization_Checker_Interface;
use Symfony\Component\Security\Core\User\User_Interface;
/**
 * Provides an actor for the extensions using the token storage.
 *
 * @internal
 */
final class Token_Storage_Actor_Provider implements Actor_Provider_Interface
{
    public function __construct(private readonly ?Token_Storage_Interface $token_storage = null, private readonly ?Authorization_Checker_Interface $authorization_checker = null)
    {
    }
    public function get_actor(): ?User_Interface
    {
        if (null === $this->token_storage || null === $this->authorization_checker) {
            return null;
        }
        $token = $this->token_storage->get_token();
        if (null === $token || !$this->authorization_checker->is_granted('IS_AUTHENTICATED_REMEMBERED')) {
            return null;
        }
        return $token->get_user();
    }
}