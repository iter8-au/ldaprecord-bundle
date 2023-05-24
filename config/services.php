<?php

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator) {
    $containerConfigurator
        ->services()
            ->set('iter8_ldap_record.connection', LdapRecord\Connection::class)
            ->public();
};
