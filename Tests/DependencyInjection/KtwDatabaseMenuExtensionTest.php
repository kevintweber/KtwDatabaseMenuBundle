<?php

/*
 * This file is part of the KtwDatabaseMenuBundle package.
 *
 * (c) Kevin T. Weber <https://github.com/kevintweber/KtwDatabaseMenuBundle/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace kevintweber\KtwDatabaseMenuBundle\Tests\DependencyInjection;

use kevintweber\KtwDatabaseMenuBundle\DependencyInjection\KtwDatabaseMenuExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Parameter;
use Symfony\Component\DependencyInjection\Reference;

class KtwDatabaseMenuExtensionTest extends \PHPUnit_Framework_TestCase
{
    public function testDefault()
    {
        $container = new ContainerBuilder();
        $loader = new KtwDatabaseMenuExtension();
        $loader->load(array(array()), $container);
        $this->assertEquals('kevintweber\KtwDatabaseMenuBundle\Entity\MenuItem', $container->getParameter('ktw_database_menu.menu_item_entity'));
    }
}