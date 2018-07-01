<?php

declare(strict_types=1);

namespace Umber\Authentication\Framework\Symfony\Bundle\DependencyInjection;

use Umber\Common\Framework\Symfony\AbstractExtension;

/**
 * {@inheritdoc}
 */
final class UmberAuthenticationExtension extends AbstractExtension
{
    /**
     * {@inheritdoc}
     */
    protected function configs(): array
    {
        return [
            'authentication',
        ];
    }
}
