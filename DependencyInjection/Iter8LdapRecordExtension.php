<?php

declare(strict_types=1);

namespace Iter8\Bundle\LdapRecordBundle\DependencyInjection;

use InvalidArgumentException;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;

final class Iter8LdapRecordExtension extends ConfigurableExtension
{
    public const EXCEPTION_TLS_AND_SSL = 'Cannot configure LDAP connection to use both TLS and SSL, please pick one.';

    /**
     * {@inheritDoc}
     */
    protected function loadInternal(
        array $mergedConfig,
        ContainerBuilder $container
    ): void {
        if ($mergedConfig['use_ssl'] && $mergedConfig['use_tls']) {
            throw new InvalidArgumentException(self::EXCEPTION_TLS_AND_SSL);
        }

        $shouldAutoConnect = $mergedConfig['auto_connect'];
        unset($mergedConfig['auto_connect']);

        $container
            ->getDefinition('iter8_ldap_record.connection')
            ->replaceArgument(0, $mergedConfig);

        if (!$shouldAutoConnect) {
            return;
        }

        $container
            ->getDefinition('iter8_ldap_record.connection')
            ->addMethodCall('connect');
    }
}
