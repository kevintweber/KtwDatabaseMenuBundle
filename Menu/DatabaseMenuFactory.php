<?php

/*
 * This file is part of the KtwDatabaseMenuBundle package.
 *
 * (c) Kevin T. Weber <https://github.com/kevintweber/KtwDatabaseMenuBundle/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace kevintweber\KtwDatabaseMenuBundle\Menu;

use kevintweber\KtwDatabaseMenuBundle\Entity\MenuItem as KtwMenuItem;
use Knp\Menu\Silex\RouterAwareFactory;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class DatabaseMenuFactory extends RouterAwareFactory
{
    protected $container;

    /**
     * Constructor
     */
    public function __construct(UrlGeneratorInterface $generator,
                                ContainerInterface $container)
    {
        $this->container = $container;
        parent::__construct($generator);
    }

    public function createItem($name, array $options = array())
    {
        $class = $this->container->getParameter('ktw_database_menu.menu_item_repository');
        $item = new $class($name, $this);

        $options = $this->buildOptions($options);
        $this->configureItem($item, $options);

        return $item;
    }
}