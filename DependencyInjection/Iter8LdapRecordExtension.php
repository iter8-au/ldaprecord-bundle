<?php

declare(strict_types=1);

namespace Iter8\Bundle\LdapRecordBundle\DependencyInjection;

use InvalidArgumentException;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;

/**
 * @internal
 */
class Iter8LdapRecordExtension extends ConfigurableExtension
{
    protected function loadInternal(array $mergedConfig, ContainerBuilder $container)
    {
        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        if ($mergedConfig['use_ssl'] && $mergedConfig['use_tls']) {
            throw new InvalidArgumentException('Cannot configure AD/LDAP connection to use both TLS and SSL, please pick one.');
        }

        $shouldAutoConnect = $mergedConfig['auto_connect'];
        unset($mergedConfig['auto_connect']);

        $container
            ->getDefinition('iter8_ldap_record.connection')
            ->replaceArgument(0, $mergedConfig);

        if ($shouldAutoConnect) {
            $container
                ->getDefinition('iter8_ldap_record.connection')
                ->addMethodCall('connect');
        }
    }
}
