<?php

declare(strict_types=1);

namespace Iter8\LdapRecordBundle\Tests;

use PHPUnit\Framework\TestCase as PHPUnitTestCase;

class TestCase extends PHPUnitTestCase
{
    /**
     * @return array<string, string|int>
     */
    protected function getLdapConfig(): array
    {
        /** @var resource|null $h */
        $h = @ldap_connect((string) getenv('LDAP_HOST'), (int) getenv('LDAP_PORT'));
        @ldap_set_option($h, \LDAP_OPT_PROTOCOL_VERSION, 3);

        if (null === $h || !@ldap_bind($h)) {
            self::markTestSkipped('No server is listening on LDAP_HOST:LDAP_PORT');
        }

        ldap_unbind($h);

        return [
            'host' => (string) getenv('LDAP_HOST'),
            'port' => (int) getenv('LDAP_PORT'),
        ];
    }
}
