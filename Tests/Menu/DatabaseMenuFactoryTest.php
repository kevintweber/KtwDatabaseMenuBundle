<?php

/*
 * This file is part of the KtwDatabaseMenuBundle package.
 *
 * (c) Kevin T. Weber <https://github.com/kevintweber/KtwDatabaseMenuBundle/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace kevintweber\KtwDatabaseMenuBundle\Tests\Menu;

use kevintweber\KtwDatabaseMenuBundle\Entity\MenuItem;
use kevintweber\KtwDatabaseMenuBundle\Tests\BaseTestCase;

/**
 * DatabaseMenuFactory tests.
 */
class DatabaseMenuFactoryTest extends BaseTestCase
{
    public function testExtensions()
    {
        $generatorMock = $this->getMock('Symfony\Component\Routing\Generator\UrlGeneratorInterface');
        $generatorMock->expects($this->any())
            ->method('generate')
            ->with('homepage', array(), false)
            ->will($this->returnValue('/foobar'));

        $factory = $this->buildFactory($generatorMock);

        $extension1 = $this->getMock('Knp\Menu\Factory\ExtensionInterface');
        $extension1->expects($this->once())
            ->method('buildOptions')
            ->with(array('foo' => 'bar'))
            ->will($this->returnValue(array('uri' => 'foobar')));
        $extension1->expects($this->once())
            ->method('buildItem')
            ->with($this->isInstanceOf('Knp\Menu\ItemInterface'), $this->contains('foobar'));

        $factory->addExtension($extension1);

        $extension2 = $this->getMock('Knp\Menu\Factory\ExtensionInterface');
        $extension2->expects($this->once())
            ->method('buildOptions')
            ->with(array('foo' => 'baz'))
            ->will($this->returnValue(array('foo' => 'bar')));
        $extension2->expects($this->once())
            ->method('buildItem')
            ->with($this->isInstanceOf('Knp\Menu\ItemInterface'), $this->contains('foobar'));

        $factory->addExtension($extension2, 10);

        $item = $factory->createItem('test', array('foo' => 'baz'));
        $this->assertEquals('foobar', $item->getUri());
    }

    public function testCreateItem()
    {
        $generatorMock = $this->getMock('Symfony\Component\Routing\Generator\UrlGeneratorInterface');
        $generatorMock->expects($this->any())
            ->method('generate')
            ->with('homepage', array(), false)
            ->will($this->returnValue('/foobar'));

        $factory = $this->buildFactory($generatorMock);

        $item = $factory->createItem('test', array(
            'uri' => 'http://example.com',
            'linkAttributes' => array('class' => 'foo'),
            'display' => false,
            'displayChildren' => false,
        ));

        $this->assertInstanceOf('Knp\Menu\ItemInterface', $item);
        $this->assertEquals('test', $item->getName());
        $this->assertFalse($item->isDisplayed());
        $this->assertFalse($item->getDisplayChildren());
        $this->assertEquals('foo', $item->getLinkAttribute('class'));
    }

    public function testCreateItemWithRoute()
    {
        $generatorMock = $this->getMock('Symfony\Component\Routing\Generator\UrlGeneratorInterface');
        $generatorMock->expects($this->once())
            ->method('generate')
            ->with('homepage', array(), false)
            ->will($this->returnValue('/foobar'));

        $factory = $this->buildFactory($generatorMock);

        $item = $factory->createItem('test_item', array('uri' => '/hello', 'route' => 'homepage'));
        $this->assertEquals('/foobar', $item->getUri());
    }

    public function testCreateItemWithRouteAndParameters()
    {
        $generatorMock = $this->getMock('Symfony\Component\Routing\Generator\UrlGeneratorInterface');
        $generatorMock->expects($this->once())
            ->method('generate')
            ->with('homepage', array('id' => 12), false)
            ->will($this->returnValue('/foobar'));

        $factory = $this->buildFactory($generatorMock);

        $item = $factory->createItem('test_item', array('route' => 'homepage', 'routeParameters' => array('id' => 12)));
        $this->assertEquals('/foobar', $item->getUri());
    }

    public function testCreateItemWithAbsoluteRoute()
    {
        $generatorMock = $this->getMock('Symfony\Component\Routing\Generator\UrlGeneratorInterface');
        $generatorMock->expects($this->once())
            ->method('generate')
            ->with('homepage', array(), true)
            ->will($this->returnValue('http://php.net'));

        $factory = $this->buildFactory($generatorMock);

        $item = $factory->createItem('test_item', array('route' => 'homepage', 'routeAbsolute' => true));
        $this->assertEquals('http://php.net', $item->getUri());
    }
}