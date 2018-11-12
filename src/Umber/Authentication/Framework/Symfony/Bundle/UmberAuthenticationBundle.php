<?php

declare(strict_types=1);

namespace Umber\Authentication\Framework\Symfony\Bundle;

use Umber\Authentication\Framework\Symfony\Bundle\DependencyInjection\UmberAuthenticationExtension;

use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * {@inheritdoc}
 */
final class UmberAuthenticationBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function getContainerExtension()
    {
        return new UmberAuthenticationExtension();
    }
}
