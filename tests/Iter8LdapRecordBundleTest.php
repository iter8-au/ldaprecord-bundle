<?php

declare(strict_types=1);

namespace Iter8\LdapRecordBundle\Tests;

use Iter8\LdapRecordBundle\Iter8LdapRecordBundle;
use LdapRecord\Connection;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;

final class Iter8LdapRecordBundleTest extends TestCase
{
    public function test_get_container_extension(): void
    {
        $bundle = new Iter8LdapRecordBundle();

        self::assertInstanceOf(
            'Symfony\Component\HttpKernel\Bundle\BundleExtension',
            $bundle->getContainerExtension(),
        );
        self::assertSame('Iter8\LdapRecordBundle', $bundle->getNamespace());
        self::assertSame('Iter8LdapRecordBundle', $bundle->getName());
    }

    public function test_cannot_configure_tls_and_ssl_for_connection(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Cannot configure LDAP connection to use both TLS and SSL, please pick one.');

        $bundle = new Iter8LdapRecordBundle();

        $container = $this->createContainer();
        $container->registerExtension($bundle->getContainerExtension());
        $container->loadFromExtension('iter8_ldap_record', array_merge($this->baseConfig(), ['use_ssl' => true, 'use_tls' => true]));
        $container->compile();
    }

    public function test_load_empty_configuration(): void
    {
        $this->expectException(InvalidConfigurationException::class);

        $bundle = new Iter8LdapRecordBundle();

        $container = $this->createContainer();
        $container->registerExtension($bundle->getContainerExtension());
        $container->loadFromExtension('iter8_ldap_record', []);
        $container->compile();
    }

    public function test_load_valid_configuration(): void
    {
        $bundle = new Iter8LdapRecordBundle();

        $container = $this->createContainer();
        $container->registerExtension($bundle->getContainerExtension());
        $container->loadFromExtension('iter8_ldap_record', $this->baseConfig());
        $container->compile();

        self::assertTrue($container->getDefinition('iter8_ldap_record.connection')->isPublic());
    }

    public function test_is_connected_with_auto_connect_disabled(): void
    {
        $this->getLdapConfig();

        $bundle = new Iter8LdapRecordBundle();

        $container = $this->createContainer();
        $container->registerExtension($bundle->getContainerExtension());
        $container->loadFromExtension('iter8_ldap_record', $this->baseConfig());
        $container->compile();

        /** @var Connection $connection */
        $connection = $container->get('iter8_ldap_record.connection');

        self::assertFalse($connection->isConnected());
    }

    public function test_is_connected_with_auto_connect_enabled(): void
    {
        $this->getLdapConfig();

        $config = array_merge(
            $this->baseConfig(),
            ['auto_connect' => true]
        );

        $bundle = new Iter8LdapRecordBundle();

        $container = $this->createContainer();
        $container->registerExtension($bundle->getContainerExtension());
        $container->loadFromExtension('iter8_ldap_record', $config);
        $container->compile();

        /** @var Connection $connection */
        $connection = $container->get('iter8_ldap_record.connection');

        self::assertTrue($connection->isConnected());
    }

    /**
     * @return array<string, mixed>
     */
    private function baseConfig(): array
    {
        return [
            'hosts' => ['localhost'],
            'base_dn' => 'dc=local,dc=com',
            'username' => 'cn=admin,dc=local,dc=com',
            'password' => 'a_great_password',
            'port' => 3389,
        ];
    }

    private function createContainer(): ContainerBuilder
    {
        return new ContainerBuilder(new ParameterBag([
            'kernel.environment' => 'test',
            'kernel.build_dir' => sys_get_temp_dir(),
        ]));
    }
}
