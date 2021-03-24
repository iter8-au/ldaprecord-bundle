<?php

declare(strict_types=1);

namespace Iter8\Bundle\LdapRecordBundle\Tests;

use PHPUnit\Framework\TestCase as PHPUnitTestCase;

/**
 * @see https://github.com/symfony/symfony/blob/89fedfa/src/Symfony/Component/Ldap/Tests/LdapTestCase.php
 */
class TestCase extends PHPUnitTestCase
{
    protected function getLdapConfig(): array
    {
        /** @var resource|null $h */
        $h = @ldap_connect((string) getenv('LDAP_HOST'), (int) getenv('LDAP_PORT'));
        @ldap_set_option($h, \LDAP_OPT_PROTOCOL_VERSION, 3);

        if (!\is_resource($h) || !@ldap_bind($h)) {
            self::markTestSkipped('No server is listening on LDAP_HOST:LDAP_PORT');
        }

        ldap_unbind($h);

        return [
            'host' => getenv('LDAP_HOST'),
            'port' => (int) getenv('LDAP_PORT'),
        ];
    }

    protected function getLdapsConfig(): array
    {
        putenv("TLS_REQCERT=never");
        putenv('LDAPTLS_CIPHER_SUITE=NORMAL:!VERS-TLS1.2');

        @ldap_set_option(null, \LDAP_OPT_DEBUG_LEVEL, 7);
//        @ldap_set_option(null, \LDAP_OPT_X_TLS_CERTFILE, './certs/openldap.crt');
//        @ldap_set_option(null, \LDAP_OPT_X_TLS_KEYFILE, './certs/openldap.key');
        @ldap_set_option(null, \LDAP_OPT_X_TLS_REQUIRE_CERT, \LDAP_OPT_X_TLS_NEVER);
        /** @var resource|null $h */
        $h = @ldap_connect((string) getenv('LDAP_HOST'), (int) getenv('LDAPS_PORT'));
        @ldap_set_option($h, \LDAP_OPT_PROTOCOL_VERSION, 3);
        @ldap_set_option($h, \LDAP_OPT_REFERRALS, 0);
        @ldap_get_option($h, LDAP_OPT_DIAGNOSTIC_MESSAGE, $extended_error);
        @ldap_start_tls($h);

        if (!\is_resource($h) || !@ldap_bind($h)) {
            dump(@ldap_error($h));
            dump($extended_error);
            self::markTestSkipped(\sprintf(
                'No server is listening on LDAP_HOST:LDAPS_PORT (%s:%s)',
                getenv('LDAP_HOST'),
                getenv('LDAPS_PORT')
            ));
        }

        ldap_unbind($h);

        return [
            'host' => getenv('LDAP_HOST'),
            'port' => (int) getenv('LDAPS_PORT'),
        ];
    }
}
