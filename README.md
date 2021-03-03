LdapRecordBundle
================

[![GitHub Actions][GA Image]][GA Link]
[![Packagist][Packagist Image]][Packagist Link]

Introduction
------------
[LdapRecord](https://github.com/DirectoryTree/LdapRecord) bridge for Symfony.

Installation
------------
You can install this bundle using Composer

    composer require iter8/ldaprecord-bundle

or add the package to your `composer.json` file directly.

After you have installed the package, you just need to add the bundle to your `AppKernel.php` file::

    // in AppKernel::registerBundles()
    $bundles = [
        // ...
        new Iter8\Bundle\LdapRecordBundle\Iter8LdapRecordBundle(),
        // ...
    ];

Configuration
-------------

[LdapRecord installation](https://ldaprecord.com/docs/core/v2/installation)

[GA Image]: https://github.com/iter8-au/ldaprecord-bundle/actions/workflows/continuous-integration.yml/badge.svg
[GA Link]: https://github.com/iter8-au/ldaprecord-bundle/actions/workflows/continuous-integration.yml
[Packagist Image]: https://img.shields.io/packagist/v/iter8/ldaprecord-bundle.svg
[Packagist Link]: https://packagist.org/packages/iter8/ldaprecord-bundle
