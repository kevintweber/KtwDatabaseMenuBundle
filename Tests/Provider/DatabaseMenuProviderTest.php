<?php

/*
 * This file is part of the KtwDatabaseMenuBundle package.
 *
 * (c) Kevin T. Weber <https://github.com/kevintweber/KtwDatabaseMenuBundle/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace kevintweber\KtwDatabaseMenuBundle\Tests\Provider;

use kevintweber\KtwDatabaseMenuBundle\Entity\MenuItem;
use kevintweber\KtwDatabaseMenuBundle\Provider\DatabaseMenuProvider;
use kevintweber\KtwDatabaseMenuBundle\Tests\BaseTestCase;

class DatabaseMenuProviderTest extends BaseTestCase
{
    public function testGet()
    {
        $menuItem = $this->createMenu();
        $provider = new DatabaseMenuProvider(
            $this->createRepositoryMock($menuItem)
        );

        $storedMenuItem = $provider->get('asdf');
        $this->assertEquals($storedMenuItem, $menuItem);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testExceptionInGet()
    {
        $provider = new DatabaseMenuProvider(
            $this->createRepositoryMock()
        );
        $storedMenuItem = $provider->get('asdf');
    }

    public function testHas()
    {
        $menuItem = $this->createMenu();
        $provider = new DatabaseMenuProvider(
            $this->createRepositoryMock($menuItem)
        );

        $this->assertTrue($provider->has('asdf'));
    }

    public function testHasNot()
    {
        $provider = new DatabaseMenuProvider(
            $this->createRepositoryMock()
        );

        $this->assertFalse($provider->has('asdf'));
    }

    protected function createRepositoryMock(MenuItem $menuItem = null)
    {
        $repositoryMock = $this
            ->getMockBuilder(
                'kevintweber\KtwDatabaseMenuBundle\Repository\MenuItemRepository'
            )
            ->disableOriginalConstructor()
            ->setMethods(array('getMenuItemByName'))
            ->getMock();

        $repositoryMock->expects($this->once())
            ->method('getMenuItemByName')
            ->will($this->returnValue($menuItem));

        return $repositoryMock;
    }
}
