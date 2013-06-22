<?php

namespace kevintweber\KtwDatabaseMenuBundle\Tests\Entity;

use kevintweber\KtwDatabaseMenuBundle\Entity\MenuItem;
use kevintweber\KtwDatabaseMenuBundle\Tests\BaseTestCase;

/**
 * MenuItem reorder tests.
 *
 * Since kevintweber\KtwDatabaseMenuBundle\Entity\MenuItem inherits from
 * Knp\Menu\MenuItem, I have copied many of the tests from
 * KnpMenu/tests/Knp/Menu/Tests/MenuItemReorderTest.php to here.
 * Therefore most of these tests are thanks to stof of KNP Labs.  Thank you.
 */
class MenuItemReorderTest extends BaseTestCase
{
    public function testReordering()
    {
        $factory = $this->buildFactory();
        $menu = new MenuItem('root', $factory);
        $menu->addChild('c1');
        $menu->addChild('c2');
        $menu->addChild('c3');
        $menu->addChild('c4');

        $menu['c3']->moveToFirstPosition();
        $arr = array_keys($menu->getChildren());
        $this->assertEquals(array('c3', 'c1', 'c2', 'c4'), $arr);

        $menu['c2']->moveToLastPosition();
        $arr = array_keys($menu->getChildren());
        $this->assertEquals(array('c3', 'c1', 'c4', 'c2'), $arr);

        $menu['c1']->moveToPosition(2);
        $arr = array_keys($menu->getChildren());
        $this->assertEquals(array('c3', 'c4', 'c1', 'c2'), $arr);

        $menu->reorderChildren(array('c4', 'c3', 'c2', 'c1'));
        $arr = array_keys($menu->getChildren());
        $this->assertEquals(array('c4', 'c3', 'c2', 'c1'), $arr);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testReorderingWithTooManyItemNames()
    {
        $factory = $this->buildFactory();
        $menu = new MenuItem('root', $factory);
        $menu->addChild('c1');
        $menu->reorderChildren(array('c1', 'c3'));
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testReorderingWithWrongItemNames()
    {
        $factory = $this->buildFactory();
        $menu = new MenuItem('root', $factory);
        $menu->addChild('c1');
        $menu->addChild('c2');
        $menu->reorderChildren(array('c1', 'c3'));
    }
}