<?php

declare(strict_types=1);

namespace Stof\DoctrineExtensionsBundle\Tool;

use Gedmo\Tool\IpAddressProviderInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Provides an IP address for the extensions using an IP address reference.
 *
 * @internal
 */
final class RequestStackIpAddressProvider implements IpAddressProviderInterface
{
    public function __construct(private readonly ?RequestStack $requestStack)
    {
    }

    public function getAddress(): ?string
    {
        if (null === $this->requestStack) {
            return null;
        }

        $request = $this->requestStack->getCurrentRequest();

        if (null === $request) {
            return null;
        }

        return $request->getClientIp();
    }
}
