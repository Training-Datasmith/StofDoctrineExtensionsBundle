<?php

declare (strict_types=1);
namespace Stof\Doctrine_Extensions_Bundle\Tool;

use Gedmo\Tool\Ip_Address_Provider_Interface;
use Symfony\Component\Http_Foundation\Request_Stack;
/**
 * Provides an IP address for the extensions using an IP address reference.
 *
 * @internal
 */
final class Request_Stack_Ip_Address_Provider implements Ip_Address_Provider_Interface
{
    public function __construct(private readonly ?Request_Stack $request_stack)
    {
    }
    public function get_address(): ?string
    {
        if (null === $this->request_stack) {
            return null;
        }
        $request = $this->request_stack->get_current_request();
        if (null === $request) {
            return null;
        }
        return $request->get_client_ip();
    }
}