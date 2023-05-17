LdapRecordBundle
================

Integrates `LdapRecord`_ with Symfony 6.1+.

LdapRecord Library
------------------

The LdapRecord package has `excellent documentation`_ for using the library itself.

Installation
------------

Run this code in your terminal of choice:

.. code-block:: terminal

    $ composer require iter8/ldaprecord-bundle

If you don't use `Symfony Flex`_, you must enable the bundle manually in the application:

.. code-block:: php

    // config/bundles.php
    // in older Symfony apps, enable the bundle in app/AppKernel.php
    return [
        // ...
        Iter8\LdapRecordBundle\Iter8LdapRecordBundle::class => ['all' => true],
    ];

Configuration
-------------

Create the following file and configure it for your application:

.. code-block:: yaml

    # config/packages/iter8_ldaprecord_bundle.yaml

    iter8_ldap_record:
        hosts:
          - localhost
        base_dn: ~
        username: ~
        password: ~
        port: 389

        # Use SSL for connections (cannot be true if use_tls is true)
        use_ssl: false

        # Use TLS for connections (cannot be true if use_ssl is true)
        use_tls: false

        # Automatically connect when fetching from the container
        auto_connect: false

.. _LdapRecord: https://github.com/DirectoryTree/LdapRecord
.. _`excellent documentation`: https://ldaprecord.com/docs/core/v3/
.. _`Symfony Flex`: https://symfony.com/doc/current/setup/flex.html
