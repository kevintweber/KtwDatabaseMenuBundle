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
use kevintweber\KtwDatabaseMenuBundle\Provider\DatabaseMenuProvider;
use Knp\Menu\Integration\Symfony\RoutingExtension;

class BaseTestCase extends \PHPUnit_Framework_TestCase
{
    protected function buildFactory($urlGeneratorInterfaceMock = null)
    {
        if ($urlGeneratorInterfaceMock === null) {
            $urlGeneratorInterfaceMock = $this->getMock('Symfony\Component\Routing\Generator\UrlGeneratorInterface');
        }

        $routingExtension = new RoutingExtension($urlGeneratorInterfaceMock);

        return new DatabaseMenuFactory($routingExtension,
                                       'kevintweber\KtwDatabaseMenuBundle\Entity\MenuItem');
    }

    /**
     * Create a new MenuItem
     *
     * @param string $name
     * @param string $uri
     * @param array $attributes
     *
     * @return \Knp\Menu\MenuItem
     */
    protected function createMenu($name = 'test_menu',
                                  $uri = 'homepage',
                                  array $attributes = array())
    {
        $factory = $this->buildFactory();

        return $factory->createItem($name, array('attributes' => $attributes, 'uri' => $uri));
    }
}
