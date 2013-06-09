<?php

/*
 * This file is part of the KtwDatabaseMenuBundle package.
 *
 * (c) Kevin T. Weber <https://github.com/kevintweber/KtwDatabaseMenuBundle/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace kevintweber\KtwDatabaseMenuBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('ktw_database_menu');

        $rootNode
            ->children()
                ->scalarNode('menu_item_repository')
                    ->isRequired()
                    ->cannotBeEmpty()
                    ->defaultValue('kevintweber\KtwDatabaseMenuBundle\Entity\MenuItem')
                ->end()
            ->end();

        return $treeBuilder;
    }
}
