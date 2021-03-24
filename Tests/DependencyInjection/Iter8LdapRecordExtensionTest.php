<?php

declare(strict_types=1);

namespace Iter8\Bundle\LdapRecordBundle\Tests\DependencyInjection;

use InvalidArgumentException;
use Iter8\Bundle\LdapRecordBundle\DependencyInjection\Iter8LdapRecordExtension;
use Iter8\Bundle\LdapRecordBundle\Tests\TestCase;
use LdapRecord\Connection;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;

class Iter8LdapRecordExtensionTest extends TestCase
{
    public function test_cannot_configure_tls_and_ssl_for_connection(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Cannot configure LDAP connection to use both TLS and SSL, please pick one.');

        $extension = new Iter8LdapRecordExtension();
        $container = new ContainerBuilder();

        $extension->load([array_merge($this->baseConfig(), ['use_ssl' => true, 'use_tls' => true])], $container);
    }

    public function test_load_empty_configuration(): void
    {
        $this->expectException(InvalidConfigurationException::class);

        $this->createContainerWithConfig([]);
    }

    public function test_load_valid_configuration(): void
    {
        $ldapConfig = $this->getLdapConfig();

        $config = \array_merge(
            $this->baseConfig(),
            [
                'hosts' => [$ldapConfig['host']],
                'port' => $ldapConfig['port'],
            ]
        );

        $container = $this->createContainerWithConfig($config);

        self::assertTrue($container->getDefinition('iter8_ldap_record.connection')->isPublic());
    }

    public function test_is_connected_with_auto_connect_disabled(): void
    {
        $ldapConfig = $this->getLdapConfig();

        $config = \array_merge(
            $this->baseConfig(),
            [
                'hosts' => [$ldapConfig['host']],
                'port' => $ldapConfig['port'],
            ]
        );

        $container = $this->createContainerWithConfig($config);

        /** @var Connection $connection */
        $connection = $container->get('iter8_ldap_record.connection');

        self::assertFalse($connection->isConnected());
    }

    public function test_is_connected_with_auto_connect_enabled(): void
    {
        $ldapConfig = $this->getLdapConfig();

        $config = \array_merge(
            $this->baseConfig(),
            [
                'hosts' => [$ldapConfig['host']],
                'port' => $ldapConfig['port'],
                'auto_connect' => true,
            ]
        );

        $container = $this->createContainerWithConfig($config);

        /** @var Connection $connection */
        $connection = $container->get('iter8_ldap_record.connection');

        self::assertTrue($connection->isConnected());
    }

    public function test_manual_connect_with_unsecured_connection(): void
    {
        $ldapConfig = $this->getLdapConfig();

        $config = \array_merge(
            $this->baseConfig(),
            [
                'hosts' => [$ldapConfig['host']],
                'port' => $ldapConfig['port'],
            ]
        );

        $container = $this->createContainerWithConfig($config);

        /** @var Connection $connection */
        $connection = $container->get('iter8_ldap_record.connection');

        $connection->connect();

        self::assertTrue($connection->isConnected());
    }

    public function test_manual_connect_with_tls_connection(): void
    {
        $ldapConfig = $this->getLdapsConfig();

        $config = \array_merge(
            $this->baseConfig(),
            [
                'hosts' => [$ldapConfig['host']],
                'port' => $ldapConfig['port'],
                'use_tls' => true,
            ]
        );

        $container = $this->createContainerWithConfig($config);

        /** @var Connection $connection */
        $connection = $container->get('iter8_ldap_record.connection');

        $connection->connect();

        self::assertTrue($connection->isConnected());
    }

    public function test_can_find_user(): void
    {
        $ldapConfig = $this->getLdapConfig();

        $config = \array_merge(
            $this->baseConfig(),
            [
                'hosts' => [$ldapConfig['host']],
                'port' => $ldapConfig['port'],
            ]
        );

        $container = $this->createContainerWithConfig($config);

        /** @var Connection $connection */
        $connection = $container->get('iter8_ldap_record.connection');

        $results = $connection->query()->where('cn', '=', 'a')->get();

        dump($results);
        self::assertNotEmpty($results);
    }

    private function baseConfig(): array
    {
        return [
            'base_dn' => 'dc=local,dc=com',
            'username' => 'cn=admin,dc=local,dc=com',
            'password' => 'a_great_password',
        ];
    }

    private function createContainerWithConfig(array $config): ContainerBuilder
    {
        $container = $this->createContainer();
        $container->registerExtension(new Iter8LdapRecordExtension());
        $container->loadFromExtension('iter8_ldap_record', $config);
        $container->compile();

        return $container;
    }

    private function createContainer(): ContainerBuilder
    {
        return new ContainerBuilder(new ParameterBag([
            'kernel.cache_dir' => __DIR__,
            'kernel.project_dir' => __DIR__,
            'kernel.charset' => 'UTF-8',
            'kernel.debug' => false,
            'kernel.bundles' => ['Iter8LdapRecordBundle' => true],
        ]));
    }
}
