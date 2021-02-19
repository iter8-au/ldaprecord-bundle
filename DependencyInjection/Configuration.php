<?php

declare(strict_types=1);

namespace Iter8\Bundle\LdapRecordBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('iter8_ldap_record');

        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->arrayNode('hosts')
                    ->requiresAtLeastOneElement()
                    ->scalarPrototype()
                    ->end()
                ->end()
                ->scalarNode('base_dn')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('username')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('password')
                    ->isRequired()
                ->end()
                ->integerNode('port')
                    ->min(1)
                    ->max(65535)
                    ->defaultValue(389)
                ->end()
                ->booleanNode('use_ssl')
                    ->defaultFalse()
                ->end()
                ->booleanNode('use_tls')
                    ->defaultFalse()
                ->end()
                ->enumNode('version')
                    ->values([2, 3])
                    ->defaultValue(3)
                ->end()
                ->integerNode('timeout')
                    ->info('Timeout (in seconds).')
                    ->defaultValue(5)
                ->end()
                ->booleanNode('follow_referrals')
                    ->defaultFalse()
                ->end()
                ->booleanNode('auto_connect')
                    ->defaultFalse()
                ->end()
                ->arrayNode('options')
                    ->arrayPrototype()
                        ->children()
                            ->scalarNode('name')->end()
                            ->scalarNode('value')->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
