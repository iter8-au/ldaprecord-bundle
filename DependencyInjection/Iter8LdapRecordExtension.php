<?php

declare(strict_types=1);

namespace Iter8\Bundle\LdapRecordBundle\DependencyInjection;

use InvalidArgumentException;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;

final class Iter8LdapRecordExtension extends ConfigurableExtension
{
    protected function loadInternal(
        array $mergedConfig,
        ContainerBuilder $container
    ): void {
        if ($mergedConfig['use_ssl'] && $mergedConfig['use_tls']) {
            throw new InvalidArgumentException('Cannot configure LDAP connection to use both TLS and SSL, please pick one.');
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
