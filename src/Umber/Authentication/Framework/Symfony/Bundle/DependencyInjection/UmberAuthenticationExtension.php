<?php

declare(strict_types=1);

namespace Umber\Authentication\Framework\Symfony\Bundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * {@inheritdoc}
 */
final class UmberAuthenticationExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        /** @var string $directory */
        $directory = realpath(sprintf('%s/../Resources/config/services', __DIR__));

        $loader = new YamlFileLoader($container, new FileLocator($directory));
        $loader->load('authentication.yaml');
    }
}
