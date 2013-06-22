<?php

namespace kevintweber\KtwDatabaseMenuBundle\Tests;

use kevintweber\KtwDatabaseMenuBundle\Menu\DatabaseMenuFactory;

class BaseTestCase extends \PHPUnit_Framework_TestCase
{
    protected function buildFactory($urlGeneratorInterfaceMock = null)
    {
        if ($urlGeneratorInterfaceMock === null) {
            $urlGeneratorInterfaceMock = $this->getMock('Symfony\Component\Routing\Generator\UrlGeneratorInterface');
        }

        return new DatabaseMenuFactory($urlGeneratorInterfaceMock,
                                       $this->getContainerInterfaceMock());
    }

    protected function getContainerInterfaceMock()
    {
        $containerInterfaceMock = $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface');
        $containerInterfaceMock->expects($this->any())
            ->method('getParameter')
            ->with($this->logicalOr(
                 $this->equalTo('ktw_database_menu.menu_item_repository'),
                 $this->equalTo('ktw_database_menu.preload_menus')
             ))
            ->will($this->returnCallback(array($this, 'getContainerParameter')));

        return $containerInterfaceMock;
    }

    public function getContainerParameter($name)
    {
        if ($name == 'ktw_database_menu.menu_item_repository') {
            return 'kevintweber\KtwDatabaseMenuBundle\Entity\MenuItem';
        }

        if ($name == 'ktw_database_menu.preload_menus') {
            return false;
        }

        return null;
    }
}