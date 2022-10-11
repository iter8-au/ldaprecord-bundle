<?php

declare(strict_types=1);

namespace Iter8\Bundle\LdapRecordBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    /**
     * @psalm-return TreeBuilder<'array'>
     *
     * @phpstan-return TreeBuilder
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('iter8_ldap_record');

        $rootNode = $treeBuilder->getRootNode();

        $this->addHostsSection($rootNode);
        $this->addBaseDnSection($rootNode);
        $this->addUsernameSection($rootNode);
        $this->addPasswordSection($rootNode);
        $this->addPortSection($rootNode);
        $this->addUseSslSection($rootNode);
        $this->addUseTlsSection($rootNode);
        $this->addVersionSection($rootNode);
        $this->addTimeoutSection($rootNode);
        $this->addFollowReferralsSection($rootNode);
        $this->addAutoConnectSection($rootNode);
        $this->addOptionsSection($rootNode);

        return $treeBuilder;
    }

    private function addHostsSection(ArrayNodeDefinition $rootNode): void
    {
        /*
         * @psalm-suppress PossiblyNullReference
         * @psalm-suppress PossiblyUndefinedMethod
         * @psalm-suppress ReservedWord
         */
        $rootNode
            ->children()
                ->arrayNode('hosts')
                    ->requiresAtLeastOneElement()
                    ->scalarPrototype()->end()
                ->end()
            ->end();
    }

    private function addBaseDnSection(ArrayNodeDefinition $rootNode): void
    {
        /*
         * @psalm-suppress PossiblyNullReference
         * @psalm-suppress PossiblyUndefinedMethod
         * @psalm-suppress ReservedWord
         * @psalm-suppress UnusedMethodCall
         */
        $rootNode
            ->children()
                ->scalarNode('base_dn')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
            ->end();
    }

    private function addUsernameSection(ArrayNodeDefinition $rootNode): void
    {
        /*
         * @psalm-suppress PossiblyNullReference
         * @psalm-suppress PossiblyUndefinedMethod
         * @psalm-suppress ReservedWord
         * @psalm-suppress UnusedMethodCall
         */
        $rootNode
            ->children()
                ->scalarNode('username')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
            ->end();
    }

    private function addPasswordSection(ArrayNodeDefinition $rootNode): void
    {
        /*
         * @psalm-suppress PossiblyNullReference
         * @psalm-suppress PossiblyUndefinedMethod
         * @psalm-suppress ReservedWord
         * @psalm-suppress UnusedMethodCall
         */
        $rootNode
            ->children()
                ->scalarNode('password')
                    ->isRequired()
                ->end()
            ->end();
    }

    private function addPortSection(ArrayNodeDefinition $rootNode): void
    {
        /*
         * @psalm-suppress PossiblyNullReference
         * @psalm-suppress PossiblyUndefinedMethod
         * @psalm-suppress ReservedWord
         * @psalm-suppress UnusedMethodCall
         */
        $rootNode
            ->children()
                ->integerNode('port')
                    ->min(1)
                    ->max(65535)
                    ->defaultValue(389)
                ->end()
            ->end();
    }

    private function addUseSslSection(ArrayNodeDefinition $rootNode): void
    {
        /*
         * @psalm-suppress PossiblyNullReference
         * @psalm-suppress PossiblyUndefinedMethod
         * @psalm-suppress ReservedWord
         * @psalm-suppress UnusedMethodCall
         */
        $rootNode
            ->children()
                ->booleanNode('use_ssl')
                    ->defaultFalse()
                ->end()
            ->end();
    }

    private function addUseTlsSection(ArrayNodeDefinition $rootNode): void
    {
        /*
         * @psalm-suppress PossiblyNullReference
         * @psalm-suppress PossiblyUndefinedMethod
         * @psalm-suppress ReservedWord
         * @psalm-suppress UnusedMethodCall
         */
        $rootNode
            ->children()
                ->booleanNode('use_tls')
                    ->defaultFalse()
                ->end()
            ->end();
    }

    private function addVersionSection(ArrayNodeDefinition $rootNode): void
    {
        /*
         * @psalm-suppress PossiblyNullReference
         * @psalm-suppress PossiblyUndefinedMethod
         * @psalm-suppress ReservedWord
         * @psalm-suppress UnusedMethodCall
         */
        $rootNode
            ->children()
                ->enumNode('version')
                    ->values([2, 3])
                    ->defaultValue(3)
                ->end()
            ->end();
    }

    private function addTimeoutSection(ArrayNodeDefinition $rootNode): void
    {
        /*
         * @psalm-suppress PossiblyNullReference
         * @psalm-suppress PossiblyUndefinedMethod
         * @psalm-suppress ReservedWord
         * @psalm-suppress UnusedMethodCall
         */
        $rootNode
            ->children()
                ->integerNode('timeout')
                    ->info('Timeout (in seconds).')
                    ->defaultValue(5)
                ->end()
            ->end();
    }

    private function addFollowReferralsSection(ArrayNodeDefinition $rootNode): void
    {
        /*
         * @psalm-suppress PossiblyNullReference
         * @psalm-suppress PossiblyUndefinedMethod
         * @psalm-suppress ReservedWord
         * @psalm-suppress UnusedMethodCall
         */
        $rootNode
            ->children()
                ->booleanNode('follow_referrals')
                    ->defaultFalse()
                ->end()
            ->end();
    }

    private function addAutoConnectSection(ArrayNodeDefinition $rootNode): void
    {
        /*
         * @psalm-suppress PossiblyNullReference
         * @psalm-suppress PossiblyUndefinedMethod
         * @psalm-suppress ReservedWord
         * @psalm-suppress UnusedMethodCall
         */
        $rootNode
            ->children()
                ->booleanNode('auto_connect')
                    ->defaultFalse()
                ->end()
            ->end();
    }

    private function addOptionsSection(ArrayNodeDefinition $rootNode): void
    {
        /*
         * @psalm-suppress PossiblyNullReference
         * @psalm-suppress PossiblyUndefinedMethod
         * @psalm-suppress ReservedWord
         */
        $rootNode
            ->children()
                ->arrayNode('options')
                    ->arrayPrototype()
                        ->children()
                            ->scalarNode('name')->end()
                            ->scalarNode('value')->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
    }
}
