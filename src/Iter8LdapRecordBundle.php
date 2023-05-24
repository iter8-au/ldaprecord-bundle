<?php

declare(strict_types=1);

namespace Iter8\LdapRecordBundle;

use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

final class Iter8LdapRecordBundle extends AbstractBundle
{
    public function configure(DefinitionConfigurator $definition): void
    {
        $definition->import('../config/definition.php');
    }

    /**
     * @phpstan-param array{hosts: list<string>, base_dn: string, username: string, password: string, port: int, use_ssl: bool, use_tls: bool, version: int, timeout: int, follow_referrals: bool, auto_connect: bool, options: array<array-key, mixed>} $config
     */
    public function loadExtension(
        array $config,
        ContainerConfigurator $container,
        ContainerBuilder $builder,
    ): void {
        if ($config['use_ssl'] && $config['use_tls']) {
            throw new \InvalidArgumentException('Cannot configure LDAP connection to use both TLS and SSL, please pick one.');
        }

        $shouldAutoConnect = $config['auto_connect'];
        unset($config['auto_connect']);

        $container->import('../config/services.php');

        $container
            ->services()
            ->get('iter8_ldap_record.connection')
            ->arg(0, $config);

        if (!$shouldAutoConnect) {
            return;
        }

        $container
            ->services()
            ->get('iter8_ldap_record.connection')
            ->call('connect');
    }
}
