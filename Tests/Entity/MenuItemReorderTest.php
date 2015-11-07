<?php

/*
 * This file is part of the KtwDatabaseMenuBundle package.
 *
 * (c) Kevin T. Weber <https://github.com/kevintweber/KtwDatabaseMenuBundle/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
    public function testReorderChildren()
    {
        $factory = $this->buildFactory();
        $menu = new MenuItem('root', $factory);
        $menu->addChild('c1');
        $menu->addChild('c2');
        $this->assertEquals('c1', $menu->getFirstChild()->getName());
        $menu->reorderChildren(array('c2', 'c1'));
        $this->assertEquals('c2', $menu->getFirstChild()->getName());
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