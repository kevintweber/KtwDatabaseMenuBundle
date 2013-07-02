<?php

/*
 * This file is part of the KtwDatabaseMenuBundle package.
 *
 * (c) Kevin T. Weber <https://github.com/kevintweber/KtwDatabaseMenuBundle/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace kevintweber\KtwDatabaseMenuBundle\Tests;

use kevintweber\KtwDatabaseMenuBundle\Menu\DatabaseMenuFactory;
use Knp\Menu\Silex\RoutingExtension;

class BaseTestCase extends \PHPUnit_Framework_TestCase
{
    protected function buildFactory($urlGeneratorInterfaceMock = null)
    {
        if ($urlGeneratorInterfaceMock === null) {
            $urlGeneratorInterfaceMock = $this->getMock('Symfony\Component\Routing\Generator\UrlGeneratorInterface');
        }

        $routingExtension = new RoutingExtension($urlGeneratorInterfaceMock);

        return new DatabaseMenuFactory($routingExtension,
                                       $this->getContainerInterfaceMock());
    }

    protected function getContainerInterfaceMock()
    {
        $containerInterfaceMock = $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface');
        $containerInterfaceMock->expects($this->any())
            ->method('getParameter')
            ->with($this->logicalOr(
                 $this->equalTo('ktw_database_menu.menu_item_entity'),
                 $this->equalTo('ktw_database_menu.preload_menus')
             ))
            ->will($this->returnCallback(array($this, 'getContainerParameter')));

        return $containerInterfaceMock;
    }

    public function getContainerParameter($name)
    {
        if ($name == 'ktw_database_menu.menu_item_entity') {
            return 'kevintweber\KtwDatabaseMenuBundle\Entity\MenuItem';
        }

        if ($name == 'ktw_database_menu.preload_menus') {
            return false;
        }

        return null;
    }
}