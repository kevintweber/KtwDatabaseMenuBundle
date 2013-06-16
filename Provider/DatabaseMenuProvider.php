<?php

/*
 * This file is part of the KtwDatabaseMenuBundle package.
 *
 * (c) Kevin T. Weber <https://github.com/kevintweber/KtwDatabaseMenuBundle/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace kevintweber\KtwDatabaseMenuBundle\Provider;

use kevintweber\KtwDatabaseMenuBundle\Entity\MenuItem;
use Knp\Menu\Provider\MenuProviderInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class DatabaseMenuProvider implements MenuProviderInterface
{
    /**
     * @var ServiceContainer
     */
    protected $container;

    /**
     * @var array
     */
    protected $menuItems;

    /**
     * Constructor
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->menuItems = array();
    }

    /**
     * Retrieves a menu by its name
     *
     * @param string $name
     * @param array $options
     * @return \Knp\Menu\ItemInterface
     * @throws \InvalidArgumentException if the menu does not exists
     */
    public function get($name, array $options = array())
    {
        if ($menuItem = $this->hasMenuItemInCache($name)) {
            return $menuItem;
        }

        $repositoryName = $this->container()->getParameter('ktw_database_menu.menu_item_repository');

        $menuItem = $this->container->get('doctrine')
            ->getRepository($repositoryName)
            ->findOneBy(array('name' => $name));

        if ($menuItem === null) {
            throw new \InvalidArgumentException(sprintf('The menu "%s" is not defined.', $name));
        }

        $this->cacheMenuItem($name, $menuItem);

        return $menuItem;
    }

    /**
     * Checks whether a menu exists in this provider
     *
     * @param string $name
     * @param array $options
     * @return boolean
     */
    public function has($name, array $options = array())
    {
        // Check cache first.
        if ($this->hasMenuItemInCache($name)) {
            return true;
        }

        $repositoryName = $this->container
            ->getParameter('ktw_database_menu.menu_item_repository');

        $menuItem = $this->container->get('doctrine')
            ->getRepository($repositoryName)
            ->findOneBy(array('name' => $name));

        if ($menuItem === null) {
            return false;
        }

        $this->cacheMenuItem($name, $menuItem);

        return true;
    }

    /**
     * Will store the menu item in memory.
     *
     * @param string $name
     * @param MenuItem $menuItem
     */
    protected function cacheMenuItem($name, MenuItem $menuItem)
    {
        if (!array_key_exists($name, $this->menuItems)) {
            $this->menuItems[$name] = $menuItem;
        }
    }

    /**
     * Will retrieve a cached menu item.
     *
     * @param string $name
     * @return MenuItem|false False if the named menu item is not in the cache.
     */
    protected function hasMenuItemInCache($name)
    {
        if (array_key_exists($name, $this->menuItems)) {
            return $this->menuItems[$name];
        }

        return false;
    }
}