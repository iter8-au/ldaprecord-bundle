<?php

namespace Iter8\Bundle\LdapRecordBundle\Tests\DependencyInjection;

use InvalidArgumentException;
use Iter8\Bundle\LdapRecordBundle\DependencyInjection\Iter8LdapRecordExtension;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class Iter8LdapRecordExtensionTest extends TestCase
{
    public function testCannotConfigureTlsAndSslForConnection()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Cannot configure AD/LDAP connection to use both TLS and SSL, please pick one.');

        $extension = new Iter8LdapRecordExtension();
        $container = new ContainerBuilder();

        $extension->load([array_merge($this->baseConfig(), ['use_ssl' => true, 'use_tls' => true])], $container);
    }

    private function baseConfig(): array
    {
        return [
            'hosts' => ['example_host.local'],
            'base_dn' => 'dc=local,dc=com',
            'username' => 'cn=admin,dc=local,dc=com',
            'password' => 'a_great_password',
            'auto_connect' => true,
        ];
    }
}
